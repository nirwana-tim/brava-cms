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
            ['key' => 'adsense_enabled', 'value' => '0', 'group' => 'adsense', 'type' => 'boolean'],
            ['key' => 'adsense_client_id', 'value' => '', 'group' => 'adsense', 'type' => 'text'],
            ['key' => 'adsense_slot_1', 'value' => '', 'group' => 'adsense', 'type' => 'text'],
            ['key' => 'adsense_slot_2', 'value' => '', 'group' => 'adsense', 'type' => 'text'],
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
            'adsense_enabled',
            'adsense_client_id',
            'adsense_slot_1',
            'adsense_slot_2',
        ])->delete();
    }
};
