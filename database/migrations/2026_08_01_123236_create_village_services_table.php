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
        Schema::create('village_services', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description');

            $table->string('featured_image_path', 500)->nullable();

            $table->longText('requirements')->nullable();
            $table->longText('procedures')->nullable();

            $table->string('processing_time')->nullable();
            $table->string('cost')->nullable();
            $table->string('service_hours')->nullable();

            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('downloadable_form_path', 500)->nullable();
            $table->string('downloadable_form_name')->nullable();
            $table->string('downloadable_form_disk')->nullable();
            $table->unsignedBigInteger('downloadable_form_size')->nullable();
            $table->string('downloadable_form_mime')->nullable();
            $table->string('downloadable_form_extension')->nullable();

            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->boolean('is_pinned')->default(false);
            $table->integer('sort_order')->default(0)->index();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image', 500)->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_services');
    }
};
