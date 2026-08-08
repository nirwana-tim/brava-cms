<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The `logo` / `favicon` settings were unused (the frontend serves static
     * brand assets from `public/`), so we drop them to avoid confusion in the
     * admin settings page.
     */
    public function up(): void
    {
        DB::table('settings')->whereIn('key', ['logo', 'favicon'])->delete();
    }

    public function down(): void
    {
        $settings = [
            ['key' => 'logo', 'value' => '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'favicon', 'value' => '', 'group' => 'general', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group'], 'type' => $setting['type'], 'updated_at' => now()]
            );
        }
    }
};
