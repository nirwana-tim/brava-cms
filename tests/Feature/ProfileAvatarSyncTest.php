<?php

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('profile update syncs avatar to the linked team member', function () {
    $user = User::factory()->create(['role' => 'admin']);

    TeamMember::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'position' => 'Administrator',
        'email' => $user->email,
        'is_active' => true,
    ]);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => '/storage/uploads/photo.jpg',
    ]);

    expect($user->fresh()->teamMember->avatar)->toBe('/storage/uploads/photo.jpg');
});

test('profile update without avatar clears the linked team member avatar', function () {
    $user = User::factory()->create(['role' => 'admin']);

    TeamMember::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'position' => 'Administrator',
        'email' => $user->email,
        'is_active' => true,
        'avatar' => '/storage/uploads/existing.jpg',
    ]);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => null,
    ]);

    expect($user->fresh()->teamMember->avatar)->toBeNull();
});

test('profile update works for users without a linked team member', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => '/storage/uploads/photo.jpg',
    ]);

    $response->assertRedirect(route('profile.edit'));
    expect($user->fresh()->avatar)->toBe('/storage/uploads/photo.jpg');
});

test('clearing avatar on a team member clears the linked user avatar', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $memberUser = User::factory()->create([
        'role' => 'staff',
        'avatar' => '/storage/uploads/photo.jpg',
    ]);

    $member = TeamMember::create([
        'user_id' => $memberUser->id,
        'name' => $memberUser->name,
        'position' => 'Staff',
        'email' => $memberUser->email,
        'is_active' => true,
        'avatar' => '/storage/uploads/photo.jpg',
    ]);

    $this->actingAs($superAdmin)->put(route('admin.team.update', $member), [
        'name' => $memberUser->name,
        'email' => $memberUser->email,
        'avatar' => null,
        'is_active' => true,
    ]);

    expect($memberUser->fresh()->avatar)->toBeNull();
    expect(TeamMember::find($member->id)->avatar)->toBeNull();
});

test('updating a team member with a new avatar syncs it to the linked user', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $memberUser = User::factory()->create(['role' => 'staff']);

    $member = TeamMember::create([
        'user_id' => $memberUser->id,
        'name' => $memberUser->name,
        'position' => 'Staff',
        'email' => $memberUser->email,
        'is_active' => true,
    ]);

    $this->actingAs($superAdmin)->put(route('admin.team.update', $member), [
        'name' => $memberUser->name,
        'email' => $memberUser->email,
        'avatar' => '/storage/uploads/new-photo.jpg',
        'is_active' => true,
    ]);

    expect($memberUser->fresh()->avatar)->toBe('/storage/uploads/new-photo.jpg');
    expect(TeamMember::find($member->id)->avatar)->toBe('/storage/uploads/new-photo.jpg');
});

test('replacing a profile avatar deletes the old upload file', function () {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $user = User::factory()->create([
        'role' => 'admin',
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => '/storage/uploads/new-photo.jpg',
    ]);

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
    expect($user->fresh()->avatar)->toBe('/storage/uploads/new-photo.jpg');
});

test('replacing a team member avatar deletes the old upload file', function () {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $memberUser = User::factory()->create(['role' => 'staff']);

    $member = TeamMember::create([
        'user_id' => $memberUser->id,
        'name' => $memberUser->name,
        'position' => 'Staff',
        'email' => $memberUser->email,
        'is_active' => true,
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $this->actingAs($superAdmin)->put(route('admin.team.update', $member), [
        'name' => $memberUser->name,
        'email' => $memberUser->email,
        'avatar' => '/storage/uploads/new-photo.jpg',
        'is_active' => true,
    ]);

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
});

test('soft deleting a team member keeps its avatar file for restore', function () {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $memberUser = User::factory()->create(['role' => 'staff']);

    $member = TeamMember::create([
        'user_id' => $memberUser->id,
        'name' => $memberUser->name,
        'position' => 'Staff',
        'email' => $memberUser->email,
        'is_active' => true,
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $this->actingAs($superAdmin)->delete(route('admin.team.destroy', $member));

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeTrue();
    expect(TeamMember::withTrashed()->find($member->id))->not->toBeNull();
});

test('force deleting a team member removes its avatar upload file', function () {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $memberUser = User::factory()->create(['role' => 'staff']);

    $member = TeamMember::create([
        'user_id' => $memberUser->id,
        'name' => $memberUser->name,
        'position' => 'Staff',
        'email' => $memberUser->email,
        'is_active' => true,
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $member->delete();
    $member->forceDelete();

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
});
