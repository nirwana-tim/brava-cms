<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Renumber sort_order to unique sequential values for rows whose current
     * value is duplicated. Tables without duplicates are left untouched.
     */
    public function up(): void
    {
        foreach (['services', 'faqs', 'testimonials', 'team_members'] as $table) {
            $hasDuplicates = DB::table($table)
                ->select('sort_order')
                ->groupBy('sort_order')
                ->havingRaw('COUNT(*) > 1')
                ->exists();

            if (! $hasDuplicates) {
                continue;
            }

            $rows = DB::table($table)->orderBy('sort_order')->orderBy('id')->get(['id', 'sort_order']);

            $order = 1;
            foreach ($rows as $row) {
                if ((int) $row->sort_order !== $order) {
                    DB::table($table)->where('id', $row->id)->update(['sort_order' => $order]);
                }
                $order++;
            }
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
