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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('category_id')->constrained('complaint_categories')->restrictOnDelete();
            $table->string('reporter_name');
            $table->string('reporter_email')->nullable();
            $table->string('reporter_phone', 20)->nullable();
            $table->string('title');
            $table->text('body');
            $table->string('status', 50)->default('unread');
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Dashboard Optimized Composite Indexes
            $table->index(['status', 'deleted_at', 'created_at']);
            $table->index(['category_id', 'deleted_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
