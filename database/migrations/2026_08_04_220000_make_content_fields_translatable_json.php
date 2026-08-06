<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // 1. Services
        $this->makeColumnsTranslatable('services', ['title', 'slug', 'description'], ['slug']);

        // 2. Blogs
        $this->makeColumnsTranslatable('blogs', [
            'title', 'slug', 'excerpt', 'content',
            'meta_title', 'meta_description', 'meta_keywords',
            'featured_image_alt', 'og_image_alt',
        ], ['slug']);

        // 3. Categories
        $this->makeColumnsTranslatable('categories', ['name', 'slug', 'description'], ['slug']);

        // 4. Portfolio Items
        $this->makeColumnsTranslatable('portfolio_items', [
            'title', 'slug', 'description', 'client',
            'photo_alt', 'meta_title', 'meta_description', 'meta_keywords', 'og_image_alt',
        ], ['slug']);

        // 5. Testimonials
        $this->makeColumnsTranslatable('testimonials', ['client_name', 'position', 'content']);

        // 6. Faqs
        $this->makeColumnsTranslatable('faqs', ['question', 'answer']);

        // 7. Team Members
        $this->makeColumnsTranslatable('team_members', ['name', 'position']);

        // 8. Promos
        $this->makeColumnsTranslatable('promos', [
            'title', 'slug', 'badge_text', 'discount_info', 'description',
            'wa_template', 'meta_title', 'meta_description', 'meta_keywords',
        ], ['slug']);

        // 9. Settings (value column)
        $this->backfillSettings();
    }

    /**
     * Helper to backfill and convert columns to JSON translatable format.
     */
    private function makeColumnsTranslatable(string $table, array $columns, array $uniqueSlugsToDrop = []): void
    {
        // Drop unique indexes if any
        foreach ($uniqueSlugsToDrop as $col) {
            try {
                Schema::table($table, function (Blueprint $table) use ($col) {
                    $table->dropUnique([$col]);
                });
            } catch (Throwable $e) {
                // Index might not exist or have different name, ignore
            }
        }

        // Backfill existing rows
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $updates = [];
            foreach ($columns as $column) {
                if (property_exists($row, $column)) {
                    $val = $row->{$column};
                    if ($val !== null && ! $this->isJson($val)) {
                        $updates[$column] = json_encode(['id' => (string) $val, 'en' => null]);
                    }
                }
            }

            if (! empty($updates)) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        }
    }

    private function backfillSettings(): void
    {
        $translatableKeys = [
            'site_name', 'site_description', 'default_meta_title',
            'default_meta_description', 'hero_title', 'hero_subtitle',
            'contact_address', 'footer_copyright',
        ];

        $settings = DB::table('settings')->whereIn('key', $translatableKeys)->get();
        foreach ($settings as $setting) {
            $val = $setting->value;
            if ($val !== null && ! $this->isJson($val)) {
                DB::table('settings')->where('id', $setting->id)->update([
                    'value' => json_encode(['id' => (string) $val, 'en' => null]),
                ]);
            }
        }
    }

    private function isJson($string): bool
    {
        if (! is_string($string)) {
            return false;
        }
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op or rollbacks if needed
    }
};
