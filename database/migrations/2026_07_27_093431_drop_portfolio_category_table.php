<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('portfolio_category');
    }

    public function down(): void
    {
        Schema::create('portfolio_category', function (Blueprint $table) {
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->primary(['portfolio_id', 'category_id']);
        });
    }
};
