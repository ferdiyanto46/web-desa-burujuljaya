<?php

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
        Schema::table('documents', function (Blueprint $table) {
            // Drop FK first because MySQL uses the composite index for the FK
            $table->dropForeign(['category_id']);
            $table->dropIndex('documents_public_idx');

            // Rename columns
            $table->renameColumn('file_extension', 'extension');
            $table->renameColumn('mime_type', 'mime');

            // Add new columns
            $table->string('file_name')->after('file_path');
            $table->string('disk')->default('public')->after('file_size');
            $table->string('status')->default(PublicationStatus::Draft->value)->after('category_id');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->boolean('is_pinned')->default(false)->after('published_at');

            // Drop existing column
            $table->dropColumn('is_public');
        });

        Schema::table('documents', function (Blueprint $table) {
            // Restore FK
            $table->foreign('category_id')->references('id')->on('document_categories')->restrictOnDelete();

            // Add new index to replace the old one
            $table->index(['category_id', 'status', 'deleted_at', 'created_at'], 'documents_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex('documents_status_idx');

            // Revert dropped column
            $table->boolean('is_public')->default(true);

            // Drop added columns
            $table->dropColumn(['file_name', 'disk', 'status', 'published_at', 'is_pinned']);

            // Revert renamed columns
            $table->renameColumn('extension', 'file_extension');
            $table->renameColumn('mime', 'mime_type');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('document_categories')->restrictOnDelete();
            $table->index(['category_id', 'is_public', 'deleted_at', 'created_at'], 'documents_public_idx');
        });
    }
};
