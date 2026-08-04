<?php

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('staff can view content resources index', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->get('/admin/blogs')->assertOk();
    $this->actingAs($staff)->get('/admin/services')->assertOk();
    $this->actingAs($staff)->get('/admin/categories')->assertOk();
});

test('staff can create content', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->post('/admin/blogs', [
        'title' => 'Staff Post',
        'slug' => 'staff-post',
        'content' => 'Hello from staff.',
        'status' => 'draft',
    ]);

    $response->assertRedirect(route('admin.blogs.index'));
    $this->assertDatabaseHas('blogs', ['slug->id' => 'staff-post', 'author_id' => $staff->id]);
});

test('staff cannot access team management', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->get('/admin/team')->assertForbidden();
    $this->actingAs($staff)->get('/admin/team/create')->assertForbidden();
});

test('staff cannot access settings', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->get('/admin/settings')->assertForbidden();
    $this->actingAs($staff)->put('/admin/settings', ['site_name' => 'Hacked'])->assertForbidden();
});

test('staff cannot access recycle bin', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)->get('/admin/trash')->assertForbidden();
});

test('admin can create team member with staff role', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->post('/admin/team', [
        'name' => 'New Staff',
        'position' => 'Content Editor',
        'email' => 'newstaff@brava.id',
        'create_user_account' => '1',
        'user_role' => UserRole::Staff->value,
        'user_password' => 'password123',
    ]);

    $response->assertRedirect(route('admin.team.index'));
    $user = User::where('email', 'newstaff@brava.id')->first();
    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::Staff);
});

test('admin can reset password of staff team member', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $staff = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.team.password', $team), [
        'password' => 'newpass123',
        'password_confirmation' => 'newpass123',
    ]);

    $response->assertRedirect(route('admin.team.index'));
    expect(Hash::check('newpass123', $staff->fresh()->password))->toBeTrue();
});

test('admin cannot change their own role to staff', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $team = TeamMember::create([
        'user_id' => $admin->id,
        'name' => $admin->name,
        'position' => 'Administrator',
        'email' => $admin->email,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => $admin->name,
        'position' => 'Administrator',
        'user_role' => UserRole::Staff->value,
    ]);

    $response->assertRedirect(route('admin.team.index'));
    expect($admin->fresh()->role)->toBe(UserRole::Admin);
});

test('staff cannot manage team members', function () {
    $staff = User::factory()->staff()->create();
    $target = User::factory()->create(['role' => UserRole::Admin]);

    $team = TeamMember::create([
        'user_id' => $target->id,
        'name' => $target->name,
        'position' => 'Administrator',
        'email' => $target->email,
        'is_active' => true,
    ]);

    $this->actingAs($staff)->put(route('admin.team.update', $team), ['name' => 'Hacked'])->assertForbidden();
});
