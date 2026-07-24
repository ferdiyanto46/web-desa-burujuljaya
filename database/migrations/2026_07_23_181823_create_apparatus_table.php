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
        Schema::create('apparatus', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('position_id')->constrained('apparatus_positions')->restrictOnDelete();

            $table->string('name', 255);
            $table->string('nip', 50)->nullable();
            $table->string('education', 255)->nullable();
            $table->string('photo_path', 500)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'deleted_at', 'position_id'], 'apparatus_public_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apparatus');
    }
};
