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
        Schema::create('complaint_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained('complaints')->restrictOnDelete();
            $table->string('actor_type', 50); // system, citizen, admin
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_from', 50)->nullable();
            $table->string('status_to', 50);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['complaint_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_histories');
    }
};
