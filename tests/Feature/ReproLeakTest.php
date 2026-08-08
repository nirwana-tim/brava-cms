<?php

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;

it('reproduces leak: admin demoted by superadmin still accesses team/settings', function () {
    $super = User::factory()->create(['role' => UserRole::SuperAdmin]);
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $adminTeam = TeamMember::create([
        'user_id' => $admin->id,
        'name' => $admin->name,
        'position' => 'Administrator',
        'email' => $admin->email,
        'is_active' => true,
    ]);

    $this->actingAs($super)->put(route('admin.team.update', $adminTeam), [
        'name' => $admin->name,
        'position' => 'Administrator',
        'role' => UserRole::Staff->value,
    ])->assertRedirect();

    expect($admin->fresh()->role)->toBe(UserRole::Staff);

    $this->actingAs($admin->fresh())
        ->get('/admin/team')
        ->assertForbidden();

    $this->actingAs($admin->fresh())
        ->get('/admin/settings')
        ->assertForbidden();
});

it('forbids a regular admin from editing another admin role', function () {
    $adminA = User::factory()->create(['role' => UserRole::Admin]);
    $adminB = User::factory()->create(['role' => UserRole::Admin]);

    TeamMember::create([
        'user_id' => $adminB->id,
        'name' => $adminB->name,
        'position' => 'Administrator',
        'email' => $adminB->email,
        'is_active' => true,
    ]);

    $response = $this->actingAs($adminA)->put(route('admin.team.update', $adminB->teamMember), [
        'name' => $adminB->name,
        'position' => 'Administrator',
        'role' => UserRole::Staff->value,
    ]);

    $response->assertForbidden();
    expect($adminB->fresh()->role)->toBe(UserRole::Admin);
});
