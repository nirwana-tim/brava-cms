<?php

use App\Enums\UserRole;
use App\Models\TeamMember;
use App\Models\User;

test('admin cannot see superadmin team member in teams list', function () {
    $superAdminUser = User::factory()->create([
        'name' => 'Super Admin Test',
        'email' => 'sa@brava.id',
        'role' => UserRole::SuperAdmin,
    ]);

    $superAdminTeam = TeamMember::create([
        'user_id' => $superAdminUser->id,
        'name' => $superAdminUser->name,
        'position' => 'Super Administrator',
        'email' => $superAdminUser->email,
        'is_active' => true,
    ]);

    $adminUser = User::factory()->create([
        'name' => 'Normal Admin',
        'email' => 'admin@brava.id',
        'role' => UserRole::Admin,
    ]);

    $adminTeam = TeamMember::create([
        'user_id' => $adminUser->id,
        'name' => $adminUser->name,
        'position' => 'Administrator',
        'email' => $adminUser->email,
        'is_active' => true,
    ]);

    $response = $this->actingAs($adminUser)->get(route('admin.team.index'));

    $response->assertOk();
    $response->assertSee('Normal Admin');
    $response->assertDontSee('Super Admin Test');
});

test('superadmin can see superadmin team member in teams list', function () {
    $superAdminUser = User::factory()->create([
        'name' => 'Super Admin Test',
        'email' => 'sa@brava.id',
        'role' => UserRole::SuperAdmin,
    ]);

    TeamMember::create([
        'user_id' => $superAdminUser->id,
        'name' => $superAdminUser->name,
        'position' => 'Super Administrator',
        'email' => $superAdminUser->email,
        'is_active' => true,
    ]);

    $response = $this->actingAs($superAdminUser)->get(route('admin.team.index'));

    $response->assertOk();
    $response->assertSee('Super Admin Test');
});

test('dashboard user stats count excludes superadmin', function () {
    $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);
    $admin1 = User::factory()->create(['role' => UserRole::Admin]);
    $admin2 = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin1)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertViewHas('stats', function ($stats) {
        return $stats['users'] === 2;
    });
});

test('user cannot delete their own team account', function () {
    $adminUser = User::factory()->create(['role' => UserRole::Admin]);
    $adminTeam = TeamMember::create([
        'user_id' => $adminUser->id,
        'name' => $adminUser->name,
        'position' => 'Administrator',
        'email' => $adminUser->email,
        'is_active' => true,
    ]);

    $response = $this->actingAs($adminUser)->delete(route('admin.team.destroy', $adminTeam));

    $response->assertRedirect(route('admin.team.index'));
    $response->assertSessionHas('error', 'You cannot delete your own account.');
    expect(TeamMember::where('id', $adminTeam->id)->exists())->toBeTrue();
});
