<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('category_portfolio_item')) {
            return;
        }

        Schema::create('category_portfolio_item', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('portfolio_item_id')->constrained('portfolio_items')->cascadeOnDelete();
            $table->primary(['category_id', 'portfolio_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_portfolio_item');
    }
};
