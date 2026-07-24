<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * M1 — Users Enhancement.
 *
 * Adds village-specific fields to the existing `users` table:
 *   - ulid          : 26-char sortable identifier for external references (prevents IDOR)
 *   - avatar        : relative storage path for profile photo
 *   - phone         : optional contact number (E.164-compatible, max 20 chars)
 *   - is_active     : administrative on/off switch (distinct from soft delete)
 *   - login_attempts: brute-force counter, reset on successful auth
 *   - locked_until  : account lockout timestamp; NULL means not locked
 *   - deleted_at    : soft-delete column (SoftDeletes trait)
 *
 * Index strategy:
 *   - ulid           → UNIQUE (fast lookup by external identifier)
 *   - is_active      → BTREE  (frequent filter in admin user lists)
 *   - locked_until   → BTREE  (checked on every login attempt: WHERE locked_until > NOW())
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ── Step 1: Add new columns (ulid nullable first to allow backfill) ──────
        Schema::table('users', function (Blueprint $table): void {
            // ULID: 26-char fixed-length, sortable, used in public-facing URLs
            // Starts as nullable so we can backfill existing rows before enforcing NOT NULL.
            $table->char('ulid', 26)->nullable()->unique()->after('id');

            // Profile fields
            $table->string('avatar', 500)->nullable()->after('email');
            $table->string('phone', 20)->nullable()->after('avatar');

            // Account status — distinct from soft delete.
            // Admin can deactivate a user without losing audit history.
            $table->boolean('is_active')->default(true)->after('phone');

            // Brute-force protection fields — managed by AuthService, not via mass assignment.
            $table->unsignedTinyInteger('login_attempts')->default(0)->after('remember_token');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');

            // Soft delete — preserves FK integrity for articles, gallery, etc.
            $table->softDeletes();

            // Indexes for frequent filter/sort queries
            $table->index('is_active', 'users_is_active_index');
            $table->index('locked_until', 'users_locked_until_index');
        });

        // ── Step 2: Backfill ULIDs for any pre-existing rows ────────────────────
        // Uses lazyById() to avoid loading all rows into memory at once.
        // Safe to re-run: only processes rows where ulid IS NULL.
        DB::table('users')
            ->whereNull('ulid')
            ->lazyById(100)
            ->each(function (object $user): void {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['ulid' => (string) Str::ulid()]);
            });

        // ── Step 3: Enforce NOT NULL on ulid now that all rows have a value ──────
        Schema::table('users', function (Blueprint $table): void {
            $table->char('ulid', 26)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops indexes before columns to avoid "Key column doesn't exist" errors on MySQL.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Drop indexes before dropping their columns
            $table->dropUnique('users_ulid_unique');
            $table->dropIndex('users_is_active_index');
            $table->dropIndex('users_locked_until_index');

            // Drop columns added in up()
            $table->dropColumn([
                'ulid',
                'avatar',
                'phone',
                'is_active',
                'login_attempts',
                'locked_until',
            ]);

            // SoftDeletes helper method for clean rollback
            $table->dropSoftDeletes();
        });
    }
};
