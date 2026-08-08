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
        $rows = DB::table('portfolio_items')->whereNotNull('client')->get();

        foreach ($rows as $row) {
            $decoded = json_decode($row->client, true);

            if (! is_array($decoded)) {
                continue;
            }

            $value = $decoded['id'] ?? $decoded['en'] ?? null;

            DB::table('portfolio_items')
                ->where('id', $row->id)
                ->update(['client' => $value]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $rows = DB::table('portfolio_items')->whereNotNull('client')->get();

        foreach ($rows as $row) {
            DB::table('portfolio_items')
                ->where('id', $row->id)
                ->update(['client' => json_encode(['id' => (string) $row->client, 'en' => null])]);
        }
    }
};
