<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('photo_alt', 255)->nullable()->after('photo');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('featured_image_alt', 255)->nullable()->after('featured_image');
            $table->string('og_image_alt', 255)->nullable()->after('og_image');
        });

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->string('photo_alt', 255)->nullable()->after('photo');
            $table->string('og_image_alt', 255)->nullable()->after('og_image');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('avatar_alt', 255)->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('photo_alt');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['featured_image_alt', 'og_image_alt']);
        });

        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn(['photo_alt', 'og_image_alt']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('avatar_alt');
        });
    }
};
