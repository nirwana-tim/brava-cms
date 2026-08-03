<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('portfolio_items', 'content')) {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->dropColumn('content');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('portfolio_items', 'content')) {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->longText('content')->nullable()->after('description');
            });
        }
    }
};
