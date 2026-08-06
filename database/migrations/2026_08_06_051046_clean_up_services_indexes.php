<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (self::indexExists('services', 'products_slug_unique')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropUnique('products_slug_unique');
            });
        }

        if (self::indexExists('services', 'products_is_active_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropIndex('products_is_active_index');
                $table->index('is_active', 'services_is_active_index');
            });
        } elseif (! self::indexExists('services', 'services_is_active_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('is_active', 'services_is_active_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (self::indexExists('services', 'services_is_active_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropIndex('services_is_active_index');
            });
        }
    }

    private static function indexExists(string $table, string $index): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return DB::table('sqlite_master')
                ->where('type', 'index')
                ->where('name', $index)
                ->where('tbl_name', $table)
                ->exists();
        }

        return Schema::hasIndex($table, $index);
    }
};
