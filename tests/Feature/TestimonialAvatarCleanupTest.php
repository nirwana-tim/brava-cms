<?php

use App\Enums\UserRole;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('replacing a testimonial avatar deletes the old upload file', function () {
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $testimonial = Testimonial::factory()->create([
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
        'client_name' => $testimonial->client_name,
        'content' => $testimonial->content,
        'rating' => $testimonial->rating,
        'avatar' => '/storage/uploads/new-photo.jpg',
        'is_active' => true,
    ]);

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
    expect($testimonial->fresh()->avatar)->toBe('/storage/uploads/new-photo.jpg');
});

test('deleting a testimonial removes its avatar upload file', function () {
    Storage::disk('public')->put('uploads/old-photo.jpg', 'old');

    $testimonial = Testimonial::factory()->create([
        'avatar' => '/storage/uploads/old-photo.jpg',
    ]);

    $this->actingAs($this->admin)->delete(route('admin.testimonials.destroy', $testimonial));

    expect(Storage::disk('public')->exists('uploads/old-photo.jpg'))->toBeFalse();
    expect(Testimonial::find($testimonial->id))->toBeNull();
});
