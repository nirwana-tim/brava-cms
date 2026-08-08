<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $_ENV['SUPERADMIN_EMAIL'] = 'superadmin@brava.id';
    $_ENV['SUPERADMIN_PASSWORD'] = null;
    $_ENV['ADMIN_EMAIL'] = 'admin@brava.id';
    $_ENV['ADMIN_PASSWORD'] = null;
    $_SERVER['SUPERADMIN_EMAIL'] = 'superadmin@brava.id';
    $_SERVER['SUPERADMIN_PASSWORD'] = null;
    $_SERVER['ADMIN_EMAIL'] = 'admin@brava.id';
    $_SERVER['ADMIN_PASSWORD'] = null;
});

test('DatabaseSeeder creates users from env credentials', function () {
    $_ENV['SUPERADMIN_EMAIL'] = 'sa@env.test';
    $_SERVER['SUPERADMIN_EMAIL'] = 'sa@env.test';
    $_ENV['SUPERADMIN_PASSWORD'] = 'super-secret-pass';
    $_SERVER['SUPERADMIN_PASSWORD'] = 'super-secret-pass';
    $_ENV['ADMIN_EMAIL'] = 'ad@env.test';
    $_SERVER['ADMIN_EMAIL'] = 'ad@env.test';
    $_ENV['ADMIN_PASSWORD'] = 'admin-secret-pass';
    $_SERVER['ADMIN_PASSWORD'] = 'admin-secret-pass';

    $this->seed(DatabaseSeeder::class);

    $super = User::where('email', 'sa@env.test')->first();
    expect($super)->not->toBeNull()
        ->and($super->role)->toBe(UserRole::SuperAdmin)
        ->and(auth()->validate(['email' => 'sa@env.test', 'password' => 'super-secret-pass']))->toBeTrue();

    $admin = User::where('email', 'ad@env.test')->first();
    expect($admin)->not->toBeNull()
        ->and($admin->role)->toBe(UserRole::Admin)
        ->and(auth()->validate(['email' => 'ad@env.test', 'password' => 'admin-secret-pass']))->toBeTrue();
});

test('DatabaseSeeder links team members to env-created users', function () {
    $_ENV['SUPERADMIN_EMAIL'] = 'sa@env.test';
    $_SERVER['SUPERADMIN_EMAIL'] = 'sa@env.test';
    $_ENV['SUPERADMIN_PASSWORD'] = 'pass';
    $_SERVER['SUPERADMIN_PASSWORD'] = 'pass';
    $_ENV['ADMIN_EMAIL'] = 'ad@env.test';
    $_SERVER['ADMIN_EMAIL'] = 'ad@env.test';
    $_ENV['ADMIN_PASSWORD'] = 'pass';
    $_SERVER['ADMIN_PASSWORD'] = 'pass';

    $this->seed(DatabaseSeeder::class);

    expect(TeamMember::where('email', 'sa@env.test')->exists())->toBeTrue()
        ->and(TeamMember::where('email', 'ad@env.test')->exists())->toBeTrue();
});

test('DatabaseSeeder falls back to default emails when env is empty', function () {
    $_ENV['SUPERADMIN_EMAIL'] = null;
    $_ENV['SUPERADMIN_PASSWORD'] = null;
    $_ENV['ADMIN_EMAIL'] = null;
    $_ENV['ADMIN_PASSWORD'] = null;
    $_SERVER['SUPERADMIN_EMAIL'] = null;
    $_SERVER['SUPERADMIN_PASSWORD'] = null;
    $_SERVER['ADMIN_EMAIL'] = null;
    $_SERVER['ADMIN_PASSWORD'] = null;

    $this->seed(DatabaseSeeder::class);

    expect(User::where('email', 'superadmin@brava.id')->exists())->toBeTrue()
        ->and(User::where('email', 'admin@brava.id')->exists())->toBeTrue();
});

test('DatabaseSeeder seeds only users and base settings, no demo content', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Blog::count())->toBe(0)
        ->and(Service::count())->toBe(0)
        ->and(Category::count())->toBe(0)
        ->and(Promo::count())->toBe(0)
        ->and(Faq::count())->toBe(0)
        ->and(Testimonial::count())->toBe(0)
        ->and(PortfolioItem::count())->toBe(0)
        ->and(User::count())->toBe(2)
        ->and(Setting::where('key', 'site_name')->exists())->toBeTrue();
});
