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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

            $table->string('title', 300);
            $table->string('slug', 320)->unique();
            $table->longText('content');
            $table->string('location', 255);

            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();

            $table->string('status')->default(ArticleStatus::Draft->value);
            $table->dateTime('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index yang dioptimalkan sesuai M-01
            $table->index(['status', 'deleted_at', 'start_datetime'], 'agendas_upcoming_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
