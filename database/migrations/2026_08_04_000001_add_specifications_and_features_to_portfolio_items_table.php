<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('portfolio_items', 'specifications')) {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->json('specifications')->nullable()->after('content');
            });
        }

        if (! Schema::hasColumn('portfolio_items', 'features')) {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->json('features')->nullable()->after('specifications');
            });
        }
    }

    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn(['specifications', 'features']);
        });
    }
};
