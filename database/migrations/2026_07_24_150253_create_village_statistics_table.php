<?php

declare(strict_types=1);

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
        Schema::create('village_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('statistic_categories')->restrictOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('value');
            $table->unsignedSmallInteger('year');
            $table->timestamps();

            $table->index(['category_id', 'year'], 'village_statistics_category_year_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_statistics');
    }
};
