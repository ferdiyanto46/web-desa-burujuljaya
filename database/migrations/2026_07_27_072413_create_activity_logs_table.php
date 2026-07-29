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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->char('batch_uuid', 36)->nullable();
            $table->string('log_name')->default('default');
            $table->string('event', 100);
            $table->text('description');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Only created_at, no updated_at
            $table->timestamp('created_at')->nullable();

            // Indexes
            $table->index(['log_name', 'created_at']);
            $table->index(['event', 'created_at']);
            $table->index(['causer_type', 'causer_id', 'created_at']);
            $table->index(['subject_type', 'subject_id', 'created_at']);
            $table->index('batch_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
