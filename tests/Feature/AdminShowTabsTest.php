<?php

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]));
});

test('blog show page renders id/en language tabs', function () {
    $blog = Blog::factory()->create();

    $this->get(route('admin.blogs.show', $blog))
        ->assertOk()
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English (Inggris)')
        ->assertSee('Slug (EN)');
});

test('service show page renders id/en language tabs', function () {
    $service = Service::factory()->create();

    $this->get(route('admin.services.show', $service))
        ->assertOk()
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English (Inggris)')
        ->assertSee('Slug (EN)');
});

test('portfolio show page renders id/en language tabs', function () {
    $portfolio = PortfolioItem::factory()->create();

    $this->get(route('admin.portfolio.show', $portfolio))
        ->assertOk()
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English (Inggris)')
        ->assertSee('Slug (EN)');
});

test('testimonial show page renders id/en language tabs', function () {
    $testimonial = Testimonial::factory()->create();

    $this->get(route('admin.testimonials.show', $testimonial))
        ->assertOk()
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English (Inggris)');
});

test('faq show page renders id/en language tabs', function () {
    $faq = Faq::factory()->create();

    $this->get(route('admin.faqs.show', $faq))
        ->assertOk()
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English (Inggris)');
});

test('team show page renders id/en language tabs', function () {
    $team = TeamMember::factory()->create();

    $this->get(route('admin.team.show', $team))
        ->assertOk()
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English (Inggris)');
});
