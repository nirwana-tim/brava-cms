<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Strip the scheme and host from stored absolute storage URLs so avatar
     * columns keep a relative `/storage/...` path that works on any origin.
     */
    public function up(): void
    {
        foreach (['users', 'team_members'] as $table) {
            DB::table($table)
                ->where('avatar', 'like', 'http://%')
                ->orWhere('avatar', 'like', 'https://%')
                ->orderBy('id')
                ->each(function (object $row) use ($table): void {
                    $normalized = preg_replace('#^https?://[^/]+(?=/storage/)#', '', $row->avatar ?? '');

                    if ($normalized !== $row->avatar) {
                        DB::table($table)
                            ->where('id', $row->id)
                            ->update(['avatar' => $normalized]);
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
