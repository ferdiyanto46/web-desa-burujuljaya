<?php

declare(strict_types=1);

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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('category_id')->constrained('article_categories')->restrictOnDelete();
            $table->string('title', 300);
            $table->string('slug', 320)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');
            $table->string('featured_image_path', 500)->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('og_image_path', 500)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'published_at', 'id', 'deleted_at'], 'articles_public_pagination_idx');
            $table->index(['category_id', 'status', 'deleted_at'], 'articles_category_status_idx');
            $table->index(['is_featured', 'status', 'deleted_at'], 'articles_featured_status_idx');

            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'excerpt', 'content'], 'articles_fulltext_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
