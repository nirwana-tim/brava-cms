<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Brava CMS', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'A modern, reusable Headless CMS built with Laravel.', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'address', 'value' => 'Jl. Contoh No. 123, Jakarta', 'group' => 'contact', 'type' => 'textarea'],
            ['key' => 'email', 'value' => 'hello@brava.id', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'phone', 'value' => '+62 812 3456 7890', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/bravacms', 'group' => 'social', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/bravacms', 'group' => 'social', 'type' => 'text'],
            ['key' => 'youtube_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'tiktok_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'x_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'linkedin_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'default_meta_title', 'value' => 'Brava CMS - Headless CMS Solution', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'default_meta_description', 'value' => 'Brava CMS is a modern headless CMS built with Laravel, designed for speed and SEO.', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'default_og_image', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'google_verification', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'organization_schema', 'value' => '', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'adsense_enabled', 'value' => '0', 'group' => 'adsense', 'type' => 'boolean'],
            ['key' => 'adsense_client_id', 'value' => '', 'group' => 'adsense', 'type' => 'text'],
            ['key' => 'adsense_slot_1', 'value' => '', 'group' => 'adsense', 'type' => 'text'],
            ['key' => 'adsense_slot_2', 'value' => '', 'group' => 'adsense', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
