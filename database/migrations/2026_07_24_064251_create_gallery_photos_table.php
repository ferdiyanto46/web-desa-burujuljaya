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
            $table->foreignId('album_id')->constrained('gallery_albums')->restrictOnDelete();
            $table->string('title')->nullable();
            $table->string('photo_path', 500);
            $table->unsignedInteger('size')->nullable(); // in bytes, unsigned integer allows up to 4GB
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['album_id', 'order'], 'gallery_photos_order_idx');
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
