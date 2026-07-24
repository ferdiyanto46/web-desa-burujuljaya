<?php

use App\Enums\ArticleStatus;
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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

            $table->string('title', 300);
            $table->string('slug', 320)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');

            $table->string('status')->default(ArticleStatus::Draft->value);
            $table->boolean('is_pinned')->default(false);
            $table->dateTime('end_date')->nullable();
            $table->dateTime('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index yang dioptimalkan sesuai M-01
            $table->index(['status', 'deleted_at', 'is_pinned', 'published_at'], 'announcements_public_idx');
            $table->fullText(['title', 'excerpt', 'content'], 'announcements_fulltext_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
