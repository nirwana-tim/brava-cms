<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->string('meta_keywords', 255)->nullable()->after('meta_description');
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->string('meta_title', 70)->nullable()->after('description');
            $table->string('meta_description', 160)->nullable()->after('meta_title');
            $table->string('meta_keywords', 255)->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn('meta_keywords');
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords']);
        });
    }
};
