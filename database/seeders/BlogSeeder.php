<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first();
        $categories = Category::pluck('id');

        $blogs = [
            [
                'title' => 'Getting Started with Laravel 13',
                'slug' => 'getting-started-with-laravel-13',
                'excerpt' => 'Learn the fundamentals of Laravel 13 and how to build modern web applications.',
                'content' => '<p>Laravel 13 brings exciting new features and improvements. In this post, we will explore the key highlights and how to get started with your first project.</p><p>From improved routing to better performance, Laravel 13 continues to be the framework of choice for PHP developers worldwide.</p>',
                'status' => PostStatus::Published,
                'is_featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'SEO Best Practices for 2026',
                'slug' => 'seo-best-practices-2026',
                'excerpt' => 'Stay ahead of the competition with these essential SEO strategies for 2026.',
                'content' => '<p>Search engine optimization continues to evolve. Here are the most important SEO practices you need to implement this year.</p><p>From Core Web Vitals to AI-powered search, the landscape is changing rapidly.</p>',
                'status' => PostStatus::Published,
                'is_featured' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Building Headless CMS with Laravel',
                'slug' => 'building-headless-cms-laravel',
                'excerpt' => 'Discover how to build a powerful headless CMS using Laravel and modern APIs.',
                'content' => '<p>A headless CMS separates the backend content management from the frontend presentation layer. Laravel makes this architecture simple and elegant.</p>',
                'status' => PostStatus::Published,
                'is_featured' => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'The Future of Web Development',
                'slug' => 'future-of-web-development',
                'excerpt' => 'Exploring upcoming trends and technologies shaping the web development industry.',
                'content' => '<p>From AI-assisted coding to edge computing, the future of web development looks incredibly exciting. Let us explore what is coming next.</p>',
                'status' => PostStatus::Draft,
                'is_featured' => false,
                'published_at' => null,
            ],
        ];

        foreach ($blogs as $data) {
            $blog = Blog::create(array_merge($data, ['author_id' => $author->id]));
            $blog->categories()->attach($categories->random(min(2, $categories->count())));
        }
    }
}
