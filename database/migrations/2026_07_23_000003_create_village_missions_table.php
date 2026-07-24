<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M2 — Foundation: village_missions table.
 *
 * Stores ordered mission statement bullet points for the village profile.
 * Each row represents one butir misi that is displayed on the public website.
 *
 * Replaces the originally proposed JSON column `mission` in village_profiles.
 *
 * Why a separate table (not JSON)?
 *   1. Atomicity: Reordering via drag-and-drop requires atomic `UPDATE sort_order`
 *      operations. With JSON, you'd have to deserialize → reorder → re-serialize
 *      the entire array — not atomic and prone to race conditions.
 *   2. Natural CRUD: INSERT one row, DELETE one row, UPDATE one row — clean and standard.
 *   3. Future-proofing: Adding `is_active`, `period`, or `category` per mission
 *      requires zero schema change with this design.
 *
 * sort_order is seeded with gaps (10, 20, 30, ...) to allow easy insertion
 * without requiring a full re-sort of all rows.
 *
 * Foreign Key:
 *   - CASCADE DELETE: when the village profile is deleted, all missions are deleted.
 *   - CASCADE UPDATE: if village_profile.id changes (very unlikely), FKs stay consistent.
 *
 * Index:
 *   - Composite (village_profile_id, sort_order): covers the single most common
 *     query pattern: "get all missions for profile X in sort order".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_missions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('village_profile_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->comment('FK ke village_profiles.id');

            $table->text('content')
                ->comment('Isi satu butir misi desa');

            $table->unsignedSmallInteger('sort_order')
                ->default(0)
                ->comment('Urutan tampil. Seeded dengan kelipatan 10 untuk memudahkan penyisipan');

            $table->timestamps();

            // Composite index: covers the only meaningful query for this table:
            // "SELECT * FROM village_missions WHERE village_profile_id = ? ORDER BY sort_order"
            $table->index(
                ['village_profile_id', 'sort_order'],
                'village_missions_profile_order_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_missions');
    }
};
