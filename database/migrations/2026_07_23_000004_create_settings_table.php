<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M2 — Foundation: settings table.
 *
 * Generic key-value store for runtime application configuration.
 * Allows admin to change site behavior without code deployment.
 *
 * Key naming convention: `{group}.{name}` e.g. `seo.meta_title`
 *
 * Column decisions:
 *   - key VARCHAR(100): The `group.name` format fits well within 100 chars.
 *   - value LONGTEXT: LONGTEXT (not TEXT) to safely store base64-encoded images,
 *     large JSON blobs, or rich editor content that might be stored in settings.
 *   - type VARCHAR(20) (not ENUM): allows adding new types without ALTER TABLE.
 *     Valid types: string, integer, boolean, json, text.
 *   - group VARCHAR(50): not named `setting_group` to match the approved design.
 *     NOTE: `group` is a MySQL reserved keyword but is unambiguous after WHERE/SET
 *     clauses. Laravel Query Builder wraps identifiers correctly.
 *   - options JSON: stores choices for select/radio settings as [{value, label}].
 *   - is_encrypted: flag indicating SettingService must apply Crypt::encryptString()
 *     before saving and Crypt::decryptString() when reading.
 *   - is_editable: false = system-only setting, disabled in admin UI.
 *   - sort_order: controls display order within a group in the admin UI.
 *
 * Indexes:
 *   - UNIQUE key: primary lookup, O(1) access.
 *   - group: filter by group in admin UI (WHERE group = 'seo').
 *   - is_public: API endpoint filter (WHERE is_public = 1 AND is_encrypted = 0).
 *   - (group, sort_order): render settings in group UI in correct order.
 *   - (group, is_public): common API pattern — public settings per group.
 *
 * Security note:
 *   Settings with is_encrypted = true should NEVER be exposed to the frontend.
 *   Always filter via SettingService::forPublic() or the `public` scope.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();

            $table->string('key', 100)
                ->unique()
                ->comment('Format: group.name — e.g. seo.meta_title, email.smtp_host');

            $table->longText('value')
                ->nullable()
                ->comment('Nilai setting. Mungkin ter-encrypt jika is_encrypted = true. Jangan baca langsung — gunakan SettingService');

            $table->string('type', 20)
                ->default('string')
                ->comment('Tipe data: string | integer | boolean | json | text. VARCHAR bukan ENUM agar bisa ditambah tanpa ALTER TABLE');

            $table->string('group', 50)
                ->comment('Kelompok: general | seo | social | email | appearance | maintenance');

            $table->string('label')
                ->comment('Label human-readable yang ditampilkan di admin UI');

            $table->text('description')
                ->nullable()
                ->comment('Help text yang menjelaskan kegunaan setting ini');

            $table->json('options')
                ->nullable()
                ->comment('Pilihan untuk tipe select/radio: [{value: "smtp", label: "SMTP"}]');

            $table->boolean('is_public')
                ->default(false)
                ->comment('true = boleh dikembalikan ke frontend tanpa autentikasi');

            $table->boolean('is_editable')
                ->default(true)
                ->comment('false = disabled di admin UI; hanya bisa diubah via Artisan/seeder');

            $table->boolean('is_encrypted')
                ->default(false)
                ->comment('true = value di-encrypt at rest dengan Crypt::encryptString(). Jangan expose ke frontend');

            $table->unsignedSmallInteger('sort_order')
                ->default(0)
                ->comment('Urutan tampil dalam grup di admin UI');

            $table->timestamps();

            // Individual indexes for common single-column queries
            $table->index('group', 'settings_group_idx');
            $table->index('is_public', 'settings_is_public_idx');

            // Composite index: ordered settings per group (admin UI render)
            $table->index(['group', 'sort_order'], 'settings_group_sort_idx');

            // Composite index: public settings per group (API/frontend filtering)
            $table->index(['group', 'is_public'], 'settings_group_public_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
