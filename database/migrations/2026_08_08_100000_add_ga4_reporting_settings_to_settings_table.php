<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            ['key' => 'ga4_property_id', 'value' => '', 'group' => 'system', 'type' => 'text'],
            ['key' => 'ga4_service_account_key', 'value' => '', 'group' => 'system', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group'], 'type' => $setting['type'], 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'ga4_property_id',
            'ga4_service_account_key',
        ])->delete();
    }
};
