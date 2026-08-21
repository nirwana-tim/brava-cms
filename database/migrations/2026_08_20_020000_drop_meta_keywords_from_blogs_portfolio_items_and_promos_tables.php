<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('meta_keywords');
        });

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn('meta_keywords');
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn('meta_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('meta_keywords')->nullable();
        });

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->text('meta_keywords')->nullable();
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->text('meta_keywords')->nullable();
        });
    }
};
