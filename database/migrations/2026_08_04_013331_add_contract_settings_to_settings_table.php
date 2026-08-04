<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var array<int, array{key: string, group: string, type: string}>
     */
    private const CONTRACT_SETTINGS = [
        ['key' => 'logo', 'group' => 'general', 'type' => 'text'],
        ['key' => 'favicon', 'group' => 'general', 'type' => 'text'],
        ['key' => 'default_og_image', 'group' => 'seo', 'type' => 'text'],
        ['key' => 'google_verification', 'group' => 'seo', 'type' => 'text'],
        ['key' => 'organization_schema', 'group' => 'seo', 'type' => 'textarea'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::CONTRACT_SETTINGS as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], [
                'value' => Setting::where('key', $setting['key'])->value('value'),
                'group' => $setting['group'],
                'type' => $setting['type'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn('key', array_column(self::CONTRACT_SETTINGS, 'key'))->delete();
    }
};
