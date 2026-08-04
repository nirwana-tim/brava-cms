<?php

use App\Services\AvatarService;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    AvatarService::flushExistsCache();
});

it('builds initials from first and last word', function () {
    $service = app(AvatarService::class);

    expect($service->initials('Dewi Sartika'))->toBe('DS');
    expect($service->initials('Ahmad Fauzi'))->toBe('AF');
    expect($service->initials('Ahmad'))->toBe('A');
});

it('builds initials from lowercase names and extra spaces', function () {
    $service = app(AvatarService::class);

    expect($service->initials('dewi sartika'))->toBe('DS');
    expect($service->initials('  ahmad   fauzi  '))->toBe('AF');
});

it('returns empty initials for blank names', function () {
    $service = app(AvatarService::class);

    expect($service->initials(''))->toBe('');
    expect($service->initials('   '))->toBe('');
});

it('derives a deterministic color from the name', function () {
    $service = app(AvatarService::class);

    expect($service->color('Dewi Sartika'))->toBe($service->color('Dewi Sartika'));
    expect($service->color('Ahmad Fauzi'))->toBe($service->color('Ahmad Fauzi'));
    expect($service->color('Dewi Sartika'))->toMatch('/^hsl\(\d+, \d+%, \d+%\)$/');
    expect($service->color('Dewi Sartika'))->not->toBe($service->color('Ahmad Fauzi'));
});

it('says a missing or empty avatar has no file', function () {
    $service = app(AvatarService::class);

    expect($service->hasAvatar(null))->toBeFalse();
    expect($service->hasAvatar(''))->toBeFalse();
    expect($service->hasAvatar('/storage/uploads/does-not-exist.png'))->toBeFalse();
});

it('detects an existing relative avatar', function () {
    Storage::disk('public')->put('uploads/avatar.png', 'content');

    $service = app(AvatarService::class);

    expect($service->hasAvatar('/storage/uploads/avatar.png'))->toBeTrue();
});

it('detects an existing absolute avatar url', function () {
    Storage::disk('public')->put('uploads/avatar.png', 'content');

    $service = app(AvatarService::class);

    expect($service->hasAvatar('http://localhost:8000/storage/uploads/avatar.png'))->toBeTrue();
});

it('detects an existing bare storage path avatar', function () {
    Storage::disk('public')->put('media/logo.png', 'content');

    $service = app(AvatarService::class);

    expect($service->hasAvatar('media/logo.png'))->toBeTrue();
});

it('treats data uri avatars as available', function () {
    $service = app(AvatarService::class);

    expect($service->hasAvatar('data:image/png;base64,AAAABBBB'))->toBeTrue();
});

it('caches file existence per path', function () {
    $service = app(AvatarService::class);

    expect($service->hasAvatar('/storage/uploads/cached.png'))->toBeFalse();

    Storage::disk('public')->put('uploads/cached.png', 'content');

    expect($service->hasAvatar('/storage/uploads/cached.png'))->toBeFalse();

    AvatarService::flushExistsCache();

    expect($service->hasAvatar('/storage/uploads/cached.png'))->toBeTrue();
});
