<?php

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin must provide email and password when creating a login account', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post(route('admin.team.store'), [
        'name' => ['id' => 'New Member'],
        'position' => ['id' => 'Content Editor'],
        'create_user_account' => '1',
        'role' => 'staff',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');

    $this->actingAs($admin)->post(route('admin.team.store'), [
        'name' => ['id' => 'New Member'],
        'position' => ['id' => 'Content Editor'],
        'create_user_account' => '1',
        'role' => 'staff',
        'email' => 'member@brava.id',
    ])->assertSessionHasErrors('password');
});

test('email and password are optional when not creating a login account', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)->post(route('admin.team.store'), [
        'name' => ['id' => 'New Member'],
        'position' => ['id' => 'Content Editor'],
        'create_user_account' => '0',
    ])->assertRedirect(route('admin.team.index'));

    expect(TeamMember::where('email', null)->first())->not->toBeNull()
        ->and(User::count())->toBe(1);
});

test('admin cannot edit email to empty when member has a login account', function () {
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
        'name' => ['id' => $team->name],
        'position' => ['id' => $team->position],
        'email' => '',
    ])->assertSessionHasErrors('email');
});

test('email can be empty for member without a login account', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $team = TeamMember::create([
        'name' => 'Offline Member',
        'position' => 'Consultant',
        'email' => null,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => ['id' => $team->name],
        'position' => ['id' => $team->position],
        'email' => '',
    ])->assertRedirect(route('admin.team.index'));
});

test('duplicate email on create returns validation error and does not orphan a team member', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $existing = User::factory()->staff()->create();

    $this->actingAs($admin)->post(route('admin.team.store'), [
        'name' => ['id' => 'New Member'],
        'position' => ['id' => 'Content Editor'],
        'create_user_account' => '1',
        'role' => 'staff',
        'email' => $existing->email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');

    expect(TeamMember::count())->toBe(0);
});

test('duplicate email on create returns validation error even without a login account', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    TeamMember::create([
        'name' => 'Existing Member',
        'position' => 'Consultant',
        'email' => 'member@brava.id',
        'is_active' => true,
    ]);

    $this->actingAs($admin)->post(route('admin.team.store'), [
        'name' => ['id' => 'New Member'],
        'position' => ['id' => 'Content Editor'],
        'create_user_account' => '0',
        'email' => 'member@brava.id',
    ])->assertSessionHasErrors('email');
});

test('member with a login account can keep their own email on update', function () {
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
        'name' => ['id' => $team->name],
        'position' => ['id' => $team->position],
        'email' => $staff->email,
    ])->assertRedirect(route('admin.team.index'));
});

test('duplicate email on update returns validation error', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $staff = User::factory()->staff()->create();
    $other = User::factory()->staff()->create();

    $team = TeamMember::create([
        'user_id' => $staff->id,
        'name' => $staff->name,
        'position' => 'Content Editor',
        'email' => $staff->email,
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => ['id' => $team->name],
        'position' => ['id' => $team->position],
        'email' => $other->email,
    ])->assertSessionHasErrors('email');
});

test('member without a login account cannot reuse another member email on update', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    TeamMember::create([
        'name' => 'First Member',
        'position' => 'Consultant',
        'email' => 'first@brava.id',
        'is_active' => true,
    ]);

    $team = TeamMember::create([
        'name' => 'Second Member',
        'position' => 'Consultant',
        'email' => 'second@brava.id',
        'is_active' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.team.update', $team), [
        'name' => ['id' => $team->name],
        'position' => ['id' => $team->position],
        'email' => 'first@brava.id',
    ])->assertSessionHasErrors('email');
});
