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
        // Drop old tables from previous incomplete implementation
        Schema::dropIfExists('tourisms');
        Schema::dropIfExists('tourism_categories');

        // Create Tourism Categories
        Schema::create('tourism_categories', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create Tourism Facilities
        Schema::create('tourism_facilities', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('icon')->nullable(); // Lucide icon name or path
            $table->timestamps();
        });

        // Create Tourisms
        Schema::create('tourisms', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('category_id')->constrained('tourism_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('featured_image_path')->nullable();

            // Location
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('google_maps_url', 1000)->nullable();

            // Info
            $table->string('opening_hours')->nullable();
            $table->string('ticket_price')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();

            // Enterprise flags
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->integer('featured_order')->default(0);
            $table->boolean('is_pinned')->default(false)->index();
            $table->unsignedBigInteger('view_count')->default(0);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Create Tourism Photos (Gallery)
        Schema::create('tourism_photos', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tourism_id')->constrained('tourisms')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Create Pivot table for Facilities
        Schema::create('tourism_facility_tourism', function (Blueprint $table) {
            $table->foreignId('tourism_id')->constrained('tourisms')->cascadeOnDelete();
            $table->foreignId('tourism_facility_id')->constrained('tourism_facilities')->cascadeOnDelete();
            $table->primary(['tourism_id', 'tourism_facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_facility_tourism');
        Schema::dropIfExists('tourism_photos');
        Schema::dropIfExists('tourisms');
        Schema::dropIfExists('tourism_facilities');
        Schema::dropIfExists('tourism_categories');
    }
};
