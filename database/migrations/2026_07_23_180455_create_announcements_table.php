<?php

use App\Enums\PublicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

            $table->string('status')->default(PublicationStatus::Draft->value);
            $table->boolean('is_pinned')->default(false);
            $table->string('featured_image_path')->nullable();
            $table->dateTime('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index yang dioptimalkan sesuai M-01
            $table->index(['is_pinned', 'status', 'deleted_at'], 'announcements_pinned_status_idx');

            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'excerpt', 'content'], 'announcements_fulltext_idx');
            }
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
