<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete()->after('id');

            $table->dropColumn([
                'meta_title', 'meta_description', 'meta_keywords',
                'og_title', 'og_description', 'og_image',
                'canonical_url', 'robots_index', 'robots_follow', 'schema_type',
            ]);
        });

    }

    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');

            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('og_title', 70)->nullable();
            $table->string('og_description', 160)->nullable();
            $table->string('og_image', 255)->nullable();
            $table->string('canonical_url', 255)->nullable();
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);
            $table->string('schema_type', 50)->default('CreativeWork');
        });
    }
};
