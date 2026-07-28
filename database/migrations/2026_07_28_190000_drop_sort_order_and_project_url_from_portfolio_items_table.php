<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('portfolio_items', 'sort_order')) {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->dropIndex('portfolio_items_sort_order_index');
                $table->dropColumn('sort_order');
            });
        }

        if (Schema::hasColumn('portfolio_items', 'project_url')) {
            Schema::table('portfolio_items', function (Blueprint $table) {
                $table->dropColumn('project_url');
            });
        }
    }

    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->index();
            $table->string('project_url')->nullable();
        });
    }
};
