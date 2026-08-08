<?php

use App\Enums\UserRole;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

function superAdminUser(): User
{
    return User::factory()->create(['role' => UserRole::SuperAdmin]);
}

function sortOrderCases(): array
{
    return [
        [
            'route' => 'admin.services.store',
            'model' => Service::class,
            'payload' => fn () => ['title' => ['id' => fake()->sentence(2)], 'slug' => ['id' => fake()->slug()]],
        ],
        [
            'route' => 'admin.faqs.store',
            'model' => Faq::class,
            'payload' => fn () => ['question' => ['id' => fake()->sentence().'?'], 'answer' => ['id' => fake()->paragraph()]],
        ],
        [
            'route' => 'admin.testimonials.store',
            'model' => Testimonial::class,
            'payload' => fn () => ['client_name' => ['id' => fake()->company()], 'content' => ['id' => fake()->paragraph()], 'rating' => 5],
        ],
    ];
}

foreach (sortOrderCases() as $case) {
    $storeRoute = $case['route'];
    $updateRoute = str_replace('.store', '.update', $storeRoute);
    $modelClass = $case['model'];
    $payload = $case['payload'];

    it("rejects creating a {$modelClass} with a duplicate sort_order", function () use ($modelClass, $storeRoute, $payload) {
        $modelClass::factory()->create(['sort_order' => 7]);

        $this->actingAs(superAdminUser())
            ->post(route($storeRoute), [...$payload(), 'sort_order' => 7])
            ->assertSessionHasErrors('sort_order');

        expect(session('errors')->first('sort_order'))
            ->toContain('Urutan (Sort Order) 7 sudah dipakai');

        expect($modelClass::count())->toBe(1);
    });

    it("auto-assigns the next sort_order when omitted on {$modelClass} create", function () use ($modelClass, $storeRoute, $payload) {
        $modelClass::factory()->create(['sort_order' => 3]);

        $this->actingAs(superAdminUser())
            ->post(route($storeRoute), $payload())
            ->assertRedirect();

        expect($modelClass::max('sort_order'))->toBe(4);
    });

    it("allows updating a {$modelClass} while keeping its own sort_order", function () use ($modelClass, $updateRoute, $payload) {
        $existing = $modelClass::factory()->create(['sort_order' => 2]);
        $modelClass::factory()->create(['sort_order' => 3]);

        $this->actingAs(superAdminUser())
            ->put(route($updateRoute, $existing), [...$payload(), 'sort_order' => 2])
            ->assertRedirect();

        expect($existing->fresh()->sort_order)->toBe(2);
    });

    it("rejects updating a {$modelClass} to a sort_order owned by another resource", function () use ($modelClass, $updateRoute, $payload) {
        $existing = $modelClass::factory()->create(['sort_order' => 2]);
        $modelClass::factory()->create(['sort_order' => 3]);

        $this->actingAs(superAdminUser())
            ->put(route($updateRoute, $existing), [...$payload(), 'sort_order' => 3])
            ->assertSessionHasErrors('sort_order');

        expect($existing->fresh()->sort_order)->toBe(2);
    });
}

it('orders faqs deterministically by sort_order then id when ties exist', function () {
    Cache::store('api')->flush();

    $first = Faq::factory()->create(['sort_order' => 1]);
    $tieA = Faq::factory()->create(['sort_order' => 2]);
    $tieB = Faq::factory()->create(['sort_order' => 2]);

    $response = $this->getJson('/api/v1/faqs');

    $response->assertOk();
    $ids = collect($response->json('data'))->pluck('id')->all();

    $expected = [$first->id, min($tieA->id, $tieB->id), max($tieA->id, $tieB->id)];

    expect($ids)->toBe($expected);
});
