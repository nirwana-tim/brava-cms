<?php

use App\Enums\PostStatus;
use App\Models\Blog;
use App\Models\User;

function makeBlog(array $attributes = []): Blog
{
    $author = User::factory()->create();

    return Blog::create(array_merge([
        'author_id' => $author->id,
        'title' => ['id' => 'Judul', 'en' => 'Title'],
        'slug' => ['id' => 'judul', 'en' => 'title'],
        'excerpt' => ['id' => 'Ringkasan', 'en' => 'Excerpt'],
        'content' => ['id' => 'Isi', 'en' => 'Body'],
        'status' => PostStatus::Draft,
    ], $attributes));
}

test('saving a draft preserves an explicitly scheduled future published_at', function () {
    $future = now()->addDays(5);

    $blog = makeBlog(['published_at' => $future]);

    $blog->refresh();

    expect($blog->status)->toBe(PostStatus::Draft)
        ->and($blog->published_at?->toDateString())->toBe($future->toDateString());
});

test('new draft without scheduled date keeps published_at null', function () {
    $blog = makeBlog();

    $blog->refresh();

    expect($blog->published_at)->toBeNull();
});

test('publishing without a date stamps now', function () {
    $blog = makeBlog();

    $blog->update(['status' => PostStatus::Published]);

    expect($blog->fresh()->published_at)->not->toBeNull();
});

test('publishing preserves an explicitly submitted date', function () {
    $date = now()->subDays(10);

    $blog = makeBlog(['published_at' => $date]);

    $blog->update(['status' => PostStatus::Published]);

    expect($blog->fresh()->published_at?->toDateString())->toBe($date->toDateString());
});

test('scheduled published blog is hidden from API until published_at', function () {
    makeBlog(['status' => PostStatus::Published, 'published_at' => now()->addDays(3)]);

    $this->getJson('/api/v1/blogs')->assertOk()
        ->assertJsonMissing(['title' => 'Judul']);
});

test('published blog with past published_at appears in API', function () {
    makeBlog(['status' => PostStatus::Published, 'published_at' => now()->subDay()]);

    $this->getJson('/api/v1/blogs')->assertOk()
        ->assertJsonPath('data.0.title', 'Judul');
});
