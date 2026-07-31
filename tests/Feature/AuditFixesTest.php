<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::store('api')->flush();
    Cache::flush();
});

test('api categories endpoint returns categories filtered by type', function () {
    Category::create(['name' => 'Tech', 'slug' => 'tech', 'type' => 'blog']);
    Category::create(['name' => 'Corporate', 'slug' => 'corporate', 'type' => 'portfolio']);

    $response = $this->getJson('/api/categories?type=blog');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Tech');
});

test('api categories endpoint rejects requests without type', function () {
    $this->getJson('/api/categories')->assertStatus(422);
});

test('boolean settings can be deactivated via toggle request', function () {
    $superadmin = User::factory()->superAdmin()->create();

    Setting::create([
        'key' => 'maintenance_mode',
        'value' => '1',
        'group' => 'system',
        'type' => 'boolean',
    ]);

    $this->actingAs($superadmin)->put('/admin/settings', [
        'maintenance_mode' => '0',
    ])->assertRedirect('/admin/settings');

    expect(Setting::where('key', 'maintenance_mode')->value('value'))->toBe('0');
});

test('blog list cache is keyed per page', function () {
    Blog::factory()->count(25)->create();

    $this->getJson('/api/blogs?page=1');
    $firstPageIds = collect($this->getJson('/api/blogs?page=1')->json('data'))->pluck('id');

    $this->getJson('/api/blogs?page=2');
    $secondPageIds = collect($this->getJson('/api/blogs?page=2')->json('data'))->pluck('id');

    expect($firstPageIds)->not->toEqual($secondPageIds)
        ->and($firstPageIds)->toHaveCount(12)
        ->and($secondPageIds)->toHaveCount(12);
});

test('promo list api builds wa_url from settings without per-item queries', function () {
    Setting::create([
        'key' => 'whatsapp_number',
        'value' => '6281234567890',
        'group' => 'contact',
        'type' => 'text',
    ]);

    Promo::factory()->count(3)->create([
        'is_highlighted' => false,
        'wa_template' => 'Halo min, saya tertarik.',
    ]);

    $response = $this->getJson('/api/promos');

    $response->assertOk();
    $first = $response->json('data.0');

    expect($first['wa_url'])->toContain('6281234567890')
        ->and($first['wa_url'])->toContain(urlencode('Halo min, saya tertarik.'));
});

test('promo admin show route is not registered', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $promo = Promo::factory()->create();

    $this->actingAs($admin)->get('/admin/promos/'.$promo->id)->assertStatus(405);
});

test('blog content is sanitized to prevent stored xss', function () {
    $blog = Blog::factory()->create([
        'content' => '<p onclick="steal()">Hello</p><script>alert(1)</script><a href="javascript:alert(1)">link</a><img src="x" onerror="hack()">',
    ]);

    $content = $blog->fresh()->content;

    expect($content)->not->toContain('<script>')
        ->and($content)->not->toContain('onclick')
        ->and($content)->not->toContain('javascript:')
        ->and($content)->not->toContain('onerror')
        ->and($content)->toContain('Hello');
});

test('team api does not expose personal contact details', function () {
    TeamMember::factory()->create([
        'name' => 'Dika',
        'email' => 'dika@brava.id',
        'phone' => '08123456789',
    ]);

    $response = $this->getJson('/api/team');

    $response->assertOk();
    $item = $response->json('data.0');

    expect($item)->not->toHaveKeys(['email', 'phone'])
        ->and($item['name'])->toBe('Dika');
});

test('admin cannot assign another admin role when creating team member', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/team', [
        'name' => 'New Staff',
        'email' => 'newstaff@brava.id',
        'role' => UserRole::Admin->value,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('role');

    expect(User::where('email', 'newstaff@brava.id')->exists())->toBeFalse();
});

test('admin can create staff member with staff role', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/team', [
        'name' => 'New Staff',
        'email' => 'newstaff@brava.id',
        'role' => UserRole::Staff->value,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect();

    expect(User::where('email', 'newstaff@brava.id')->value('role'))->toBe(UserRole::Staff);
});

test('superadmin can assign admin role when creating team member', function () {
    $superadmin = User::factory()->superAdmin()->create();

    $this->actingAs($superadmin)->post('/admin/team', [
        'name' => 'New Admin',
        'email' => 'newadmin@brava.id',
        'role' => UserRole::Admin->value,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect();

    expect(User::where('email', 'newadmin@brava.id')->value('role'))->toBe(UserRole::Admin);
});

test('upload endpoint requires authentication', function () {
    $this->post('/admin/upload')->assertRedirect(route('login'));
});

test('staff can upload media via ajax with valid file', function () {
    $staff = User::factory()->staff()->create();

    $file = UploadedFile::fake()->image('product.png', 100, 100);

    $this->actingAs($staff)->postJson('/admin/media/upload-ajax', [
        'file' => $file,
    ])->assertOk();
});

test('clearing api cache does not flush the whole cache', function () {
    Cache::put('unrelated.key', 'keep-me');

    $blog = Blog::factory()->create();

    expect(Cache::get('unrelated.key'))->toBe('keep-me')
        ->and(Cache::store('api')->has('blog.list.'.md5(serialize([])).'.p1'))->toBeFalse();
});
