<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
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

test('admin cannot assign another admin role when creating team member', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/team', [
        'name' => 'New Staff',
        'position' => 'Staff Position',
        'email' => 'newstaff@brava.id',
        'create_user_account' => '1',
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
        'position' => 'Staff Position',
        'email' => 'newstaff@brava.id',
        'create_user_account' => '1',
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
        'position' => 'Admin Position',
        'email' => 'newadmin@brava.id',
        'create_user_account' => '1',
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

test('upload ajax persists custom alt text when provided', function () {
    $staff = User::factory()->staff()->create();

    $file = UploadedFile::fake()->image('product.png', 100, 100);

    $this->actingAs($staff)->postJson('/admin/media/upload-ajax', [
        'file' => $file,
        'alt_text' => 'Produk unggulan dari koleksi terbaru',
    ])->assertOk();

    $media = Media::latest()->first();

    expect($media)->not->toBeNull()
        ->and($media->alt_text)->toBe('Produk unggulan dari koleksi terbaru');
});

test('clearing api cache does not flush the whole cache', function () {
    Cache::put('unrelated.key', 'keep-me');

    $blog = Blog::factory()->create();

    expect(Cache::get('unrelated.key'))->toBe('keep-me')
        ->and(Cache::store('api')->has('blog.list.id.'.md5(serialize([])).'.p1'))->toBeFalse();
});

test('admin cannot update team member of another admin', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $otherAdmin = User::factory()->create(['role' => UserRole::Admin]);

    $team = TeamMember::create([
        'user_id' => $otherAdmin->id,
        'name' => $otherAdmin->name,
        'position' => 'Administrator',
        'email' => $otherAdmin->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => 'Hacked',
        'position' => 'Administrator',
        'email' => 'hacked@brava.id',
    ])->assertForbidden();
});

test('admin cannot reset password of team member of another admin', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $otherAdmin = User::factory()->create(['role' => UserRole::Admin]);

    $team = TeamMember::create([
        'user_id' => $otherAdmin->id,
        'name' => $otherAdmin->name,
        'position' => 'Administrator',
        'email' => $otherAdmin->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.password', $team), [
        'password' => 'newpass123',
        'password_confirmation' => 'newpass123',
    ])->assertForbidden();
});

test('admin can update team member of staff', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $staff = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => 'Updated Staff',
        'position' => 'Content Editor',
    ])->assertRedirect(route('admin.team.index'));

    expect($team->fresh()->name)->toBe('Updated Staff');
});

test('soft deleting a team member keeps the linked user account', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $staff = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->delete(route('admin.team.destroy', $team));

    expect(TeamMember::withTrashed()->find($team->id))->not->toBeNull()
        ->and(User::find($staff->id))->not->toBeNull();
});

test('soft deleting a team member whose user owns blogs does not error', function () {
    $superadmin = User::factory()->superAdmin()->create();
    $staff = User::factory()->staff()->create();

    Blog::factory()->create(['author_id' => $staff->id]);

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $this->actingAs($superadmin)->delete(route('admin.team.destroy', $team))
        ->assertRedirect(route('admin.team.index'));

    expect(User::find($staff->id))->not->toBeNull();
});

test('force deleting a team member whose user owns blogs keeps the user', function () {
    $superadmin = User::factory()->superAdmin()->create();
    $staff = User::factory()->staff()->create();

    Blog::factory()->create(['author_id' => $staff->id]);

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $team->delete();

    $this->actingAs($superadmin)->delete("/admin/trash/team/{$team->id}/force-delete")
        ->assertRedirect(route('admin.trash.index', ['type' => 'team']));

    expect(User::find($staff->id))->not->toBeNull()
        ->and(TeamMember::withTrashed()->find($team->id))->toBeNull();
});

test('setting a portfolio cover does not delete the media file', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $portfolio = PortfolioItem::factory()->create();
    $media = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolio->id,
    ]);

    $this->actingAs($admin)->post(route('admin.portfolio.media.set-cover', [$portfolio, $media]))
        ->assertJson(['success' => true]);

    expect(Media::find($media->id))->not->toBeNull()
        ->and($portfolio->fresh()->photo)->toBe($media->url);
});

test('staff cannot reset password of another staff team member', function () {
    $staff = User::factory()->staff()->create();
    $target = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $target->id,
        'name' => $target->name,
        'position' => 'Content Editor',
        'email' => $target->email,
        'is_active' => true,
    ]);

    $this->actingAs($staff)->put(route('admin.team.password', $team), [
        'password' => 'newpass123',
        'password_confirmation' => 'newpass123',
    ])->assertForbidden();
});

test('staff cannot delete another staff team member', function () {
    $staff = User::factory()->staff()->create();
    $target = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $target->id,
        'name' => $target->name,
        'position' => 'Content Editor',
        'email' => $target->email,
        'is_active' => true,
    ]);

    $this->actingAs($staff)->delete(route('admin.team.destroy', $team))->assertForbidden();
    expect(TeamMember::find($team->id))->not->toBeNull();
});

test('admin promo per_page is clamped to a maximum', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Promo::factory()->count(5)->create();

    $response = $this->actingAs($admin)->get('/admin/promos?per_page=999999');
    $response->assertOk();

    $promos = $response->viewData('promos');
    expect($promos->perPage())->toBeLessThanOrEqual(100);
});

test('portfolio cannot attach media owned by another item', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $portfolioA = PortfolioItem::factory()->create();
    $portfolioB = PortfolioItem::factory()->create();

    $media = Media::factory()->create([
        'mediable_type' => PortfolioItem::class,
        'mediable_id' => $portfolioA->id,
    ]);

    $this->actingAs($admin)->post(route('admin.portfolio.media.attach', $portfolioB), [
        'media_id' => $media->id,
    ])->assertJsonPath('success', false);

    expect($media->fresh()->mediable_id)->toBe($portfolioA->id);
});

test('deleting own profile when user owns blogs does not error', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Blog::factory()->create(['author_id' => $admin->id]);

    $this->actingAs($admin)->delete(route('profile.destroy'), [
        'password' => 'password',
    ])->assertRedirect();

    expect(User::find($admin->id))->not->toBeNull();
});

test('blog image url rejects javascript scheme but allows relative path', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post('/admin/blogs', [
        'title' => 'Bad Image',
        'slug' => 'bad-image',
        'status' => 'draft',
        'featured_image' => 'javascript:alert(1)',
    ])->assertSessionHasErrors('featured_image');

    $this->actingAs($admin)->post('/admin/blogs', [
        'title' => 'Good Image',
        'slug' => 'good-image',
        'status' => 'draft',
        'featured_image' => '/storage/uploads/img.jpg',
    ])->assertRedirect();

    expect(Blog::where('slug->id', 'good-image')->orWhere('slug->en', 'good-image')->value('featured_image'))->toBe('/storage/uploads/img.jpg');
});

test('sanitizer strips external background-image and fixed positioning', function () {
    $blog = Blog::factory()->create([
        'content' => '<div style="background-image:url(http://evil.com/x.png); position:fixed; top:0">Safe</div>',
    ]);

    $content = $blog->fresh()->content;

    expect($content)->not->toContain('position:fixed')
        ->and($content)->not->toContain('background-image')
        ->and($content)->toContain('Safe');
});
