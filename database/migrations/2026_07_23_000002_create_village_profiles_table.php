<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M2 — Foundation: village_profiles table.
 *
 * Singleton table — exactly one row will exist for Desa Burujuljaya.
 * Stores immutable institutional identity data (not transactional/operational data).
 *
 * Decisions:
 *   - No soft delete: village profile is permanent; it is updated, never deleted.
 *   - No UUID/ULID: singleton has no external-facing identifier requirement.
 *   - logo_path VARCHAR(500): stores a relative storage path (never a user-provided value).
 *   - hero_image intentionally omitted: belongs to `banners` table (M10).
 *   - longitude uses DECIMAL(11,7): ±180° requires 4 digits before the decimal point.
 *     DECIMAL(10,7) would only allow 3 digits — a real precision bug.
 *   - latitude uses DECIMAL(10,7): ±90° needs only 3 digits before decimal.
 *   - established_year uses SMALLINT UNSIGNED: more portable than MySQL's YEAR type,
 *     which has legacy two-digit year handling quirks.
 *
 * No additional indexes: querying a singleton always hits the PK.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_profiles', function (Blueprint $table): void {
            $table->id();

            // ── Core Identity ──────────────────────────────────────────────────
            $table->string('village_name')
                ->comment('Nama resmi desa');

            $table->string('village_code', 20)
                ->nullable()
                ->comment('Kode BPS Desa (10 digit). NULL jika belum diisi');

            // ── Administrative Area ────────────────────────────────────────────
            $table->string('district', 100)
                ->comment('Kecamatan');

            $table->string('regency', 150)
                ->comment('Kabupaten/Kota. VARCHAR(150) — beberapa nama kabupaten sangat panjang');

            $table->string('province', 100)
                ->comment('Provinsi');

            $table->string('postal_code', 10)
                ->nullable()
                ->comment('Kode pos 5 digit + buffer');

            // ── Contact ────────────────────────────────────────────────────────
            $table->text('address')
                ->comment('Alamat lengkap kantor desa');

            $table->string('phone', 20)
                ->nullable()
                ->comment('Nomor telepon resmi desa (E.164-compatible, max 15 digit)');

            $table->string('email')
                ->nullable()
                ->comment('Email resmi desa');

            $table->string('website_url', 500)
                ->nullable()
                ->comment('URL website resmi desa. VARCHAR(500) — URL bisa panjang dengan query params');

            // ── Rich Content ───────────────────────────────────────────────────
            $table->longText('history')
                ->nullable()
                ->comment('Sejarah desa. LONGTEXT untuk konten essay yang panjang');

            $table->text('vision')
                ->nullable()
                ->comment('Visi desa (paragraf singkat)');

            // ── Geographic Data ────────────────────────────────────────────────
            $table->decimal('area_hectares', 10, 2)
                ->nullable()
                ->comment('Luas wilayah dalam hektar. DECIMAL(10,2) untuk presisi 2 angka desimal');

            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->comment('Koordinat GPS lintang. DECIMAL(10,7) = 7 desimal ~1cm presisi. Range ±90°');

            $table->decimal('longitude', 11, 7)
                ->nullable()
                ->comment('Koordinat GPS bujur. DECIMAL(11,7) — 11 digit karena bujur bisa ±180° (4 digit sebelum koma)');

            $table->unsignedSmallInteger('established_year')
                ->nullable()
                ->comment('Tahun berdiri desa. SMALLINT UNSIGNED lebih portable dari MySQL YEAR type');

            // ── Media ──────────────────────────────────────────────────────────
            $table->string('logo_path', 500)
                ->nullable()
                ->comment('Path relatif logo di storage/app/public/village/logo/. BUKAN URL eksternal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_profiles');
    }
};
