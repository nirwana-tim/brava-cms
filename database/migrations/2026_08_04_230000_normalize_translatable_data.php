<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TRANSLATABLE_SETTING_KEYS = [
        'site_name',
        'site_description',
        'default_meta_title',
        'default_meta_description',
        'hero_title',
        'hero_subtitle',
        'contact_address',
        'footer_copyright',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->wrapPlainValues('promos', ['image_alt']);
        $this->wrapPlainValues('services', ['photo_alt']);
        $this->wrapPlainValues('testimonials', ['avatar_alt']);

        $settings = DB::table('settings')->get();

        foreach ($settings as $setting) {
            $value = $setting->value;

            if (in_array($setting->key, self::TRANSLATABLE_SETTING_KEYS, true)) {
                if ($value !== null && ! $this->isJsonObject($value)) {
                    $this->updateSetting($setting->id, $this->wrapValue($value));
                }

                continue;
            }

            // Repair settings that were accidentally encoded as JSON
            // when every setting was treated as translatable.
            if ($this->isJsonObject($value)) {
                $decoded = json_decode($value, true);
                $this->updateSetting($setting->id, $decoded['id'] ?? null);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->unwrapToId('promos', ['image_alt']);
        $this->unwrapToId('services', ['photo_alt']);
        $this->unwrapToId('testimonials', ['avatar_alt']);

        foreach (self::TRANSLATABLE_SETTING_KEYS as $key) {
            $setting = DB::table('settings')->where('key', $key)->first();

            if ($setting !== null && $this->isJsonObject($setting->value)) {
                $decoded = json_decode($setting->value, true);
                $this->updateSetting($setting->id, $decoded['id'] ?? null);
            }
        }
    }

    private function wrapPlainValues(string $table, array $columns): void
    {
        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            $updates = [];

            foreach ($columns as $column) {
                $value = $row->{$column};

                if ($value !== null && ! $this->isJsonObject($value)) {
                    $updates[$column] = $this->wrapValue($value);
                }
            }

            if ($updates !== []) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        }
    }

    private function unwrapToId(string $table, array $columns): void
    {
        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            $updates = [];

            foreach ($columns as $column) {
                $value = $row->{$column};

                if ($this->isJsonObject($value)) {
                    $decoded = json_decode($value, true);
                    $updates[$column] = $decoded['id'] ?? null;
                }
            }

            if ($updates !== []) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        }
    }

    private function updateSetting(int $id, ?string $value): void
    {
        DB::table('settings')->where('id', $id)->update(['value' => $value]);
    }

    private function wrapValue(mixed $value): string
    {
        return json_encode(
            ['id' => (string) $value, 'en' => null],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }

    private function isJsonObject(mixed $value): bool
    {
        if (! is_string($value) || ! str_starts_with(ltrim($value), '{')) {
            return false;
        }

        return is_array(json_decode($value, true));
    }
};
