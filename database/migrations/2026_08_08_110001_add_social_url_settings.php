<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Optional social profile URLs. Kept empty by default — the frontend only
     * renders a link when a value is filled in from the admin panel.
     */
    public function up(): void
    {
        $settings = [
            ['key' => 'youtube_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'tiktok_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'x_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'linkedin_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group'], 'type' => $setting['type'], 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'youtube_url',
            'tiktok_url',
            'x_url',
            'linkedin_url',
        ])->delete();
    }
};
