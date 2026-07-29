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
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('featured_image_path', 2048)->nullable()->after('location');
            $table->string('location_url', 2048)->nullable()->after('featured_image_path');
            $table->boolean('is_cancelled')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn(['featured_image_path', 'location_url', 'is_cancelled']);
        });
    }
};
