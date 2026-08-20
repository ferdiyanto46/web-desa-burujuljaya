<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
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
        Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'slug')) {
                $table->string('slug')->unique()->after('question');
            }
            if (! Schema::hasColumn('faqs', 'category')) {
                $table->string('category')->nullable()->after('answer');
            }
            if (! Schema::hasColumn('faqs', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('sort_order');
            }
            if (! Schema::hasColumn('faqs', 'status')) {
                $table->string('status')->default(PublicationStatus::Draft->value)->after('is_featured');
            }
            if (! Schema::hasColumn('faqs', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('faqs', 'view_count')) {
                $table->integer('view_count')->default(0)->after('published_at');
            }
            if (! Schema::hasColumn('faqs', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (! Schema::hasColumn('faqs', 'meta_description')) {
                $table->string('meta_description')->nullable();
            }
            if (! Schema::hasColumn('faqs', 'canonical_url')) {
                $table->string('canonical_url')->nullable();
            }
            if (! Schema::hasColumn('faqs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('faqs', 'deleted_at')) {
                $table->softDeletes();
            }

            if (Schema::hasColumn('faqs', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            // Irreversible cleanly due to constraints, skipping explicit down drops to avoid schema breaking on rollback.
        });
    }
};
