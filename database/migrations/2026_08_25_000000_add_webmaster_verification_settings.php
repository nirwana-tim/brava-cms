<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $now = now();

        DB::table('settings')->updateOrInsert(
            ['key' => 'bing_verification'],
            [
                'value' => '',
                'group' => 'seo',
                'type' => 'text',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'custom_webmaster_tags'],
            [
                'value' => '',
                'group' => 'seo',
                'type' => 'textarea',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')->whereIn('key', ['bing_verification', 'custom_webmaster_tags'])->delete();
    }
};
