<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Widen translatable (JSON-backed) string columns to TEXT so that longer
 * translations (stored as JSON objects with one value per locale) do not
 * exceed their previous VARCHAR limits.
 *
 * @see brava-compro contract
 */
return new class extends Migration
{
    private const TABLES = [
        'blogs' => ['title', 'slug', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords', 'featured_image_alt', 'og_image_alt'],
        'portfolio_items' => ['title', 'slug', 'description', 'client', 'photo_alt', 'meta_title', 'meta_description', 'meta_keywords', 'og_image_alt'],
        'services' => ['title', 'slug', 'description', 'photo_alt'],
        'promos' => ['title', 'slug', 'badge_text', 'discount_info', 'description', 'image_alt', 'wa_template', 'meta_title', 'meta_description', 'meta_keywords'],
        'testimonials' => ['client_name', 'content', 'avatar_alt'],
        'faqs' => ['question', 'answer'],
        'categories' => ['name', 'slug', 'description'],
        'team_members' => ['name'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::TABLES as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                foreach ($columns as $column) {
                    $blueprint->text($column)->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::TABLES as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                foreach ($columns as $column) {
                    $blueprint->string($column, 255)->nullable()->change();
                }
            });
        }
    }
};
