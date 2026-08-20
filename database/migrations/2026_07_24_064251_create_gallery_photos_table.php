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
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('gallery_id')->constrained('galleries')->restrictOnDelete();
            $table->string('image_path', 500);
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('photographer')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('mime', 50)->nullable();
            $table->string('extension', 10)->nullable();
            $table->string('disk', 20)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['gallery_id', 'sort_order'], 'gallery_photos_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
    }
};
