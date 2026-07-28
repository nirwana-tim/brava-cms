<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => '6281234567890', 'group' => 'contact', 'type' => 'text']
        );
    }
}
