# 📝 Implementation Plan — Module 9 (Documents)

> **Status**: *Refined by Senior Architect Review*

## 1. Objective
Menerjemahkan *Design Document* Modul 9 yang telah di-audit menjadi baris kode fisik yang kokoh dan efisien. Fokus dari fase implementasi ini meliputi pendirian skema _database_ mutakhir dengan ekstensi `mime_type`, penjegalan *Storage Leak* absolut di dalam Observer (pada event `forceDeleted` & `updated`), dan pembentukan indeks B-Tree yang prima untuk pengurutan data *(sorting)* massal.

## 2. Scope
- **2 Migrations**: `document_categories` dan `documents` (FK RESTRICT, penambahan kolom `mime_type`, dan *Composite Index* optimal).
- **2 Eloquent Models**: `DocumentCategory` dan `Document` yang dikunci dengan `strict_types=1` dan *final class*, lengkap dengan `casts()` dan *Query Scopes*.
- **1 Observer**: `DocumentObserver` yang akan dipasang untuk me-*trigger* pembersihan dokumen fisik lama dari `Storage` ketika berkas diubah (*updated*) maupun dihapus permanen (*forceDeleted*).
- **2 Factories**: Memproduksi struktur data tiruan dan `mime_type` generik (*application/pdf*).
- **2 Seeders**: Menanamkan *lookup* konstan dan relasi *dummy* (dibatasi `limit(20)` demi keselamatan memori).
- Modifikasi terhadap antrean akhir berkas `DatabaseSeeder.php`.

## 3. Dependency Check
- **Modul 1 (Users)**: Menjadi landasan relasi untuk mencatat aktor pengunggah (`user_id`).
- **Traits Core**: Menggantungkan diri pada `HasUlid` dan `HasUniqueSlug`.

## 4. Execution Order

**Step 1: Migrations Creation**
- Mendeploy migrasi `document_categories`.
- Mendeploy migrasi `documents` (menambahkan tipe `unsignedBigInteger` untuk *size* & *count*, dan `VARCHAR(100)` untuk `mime_type`). Menyuntikkan indeks komposit rapi `(category_id, is_public, deleted_at, created_at)`.

**Step 2: Models & Traits Assembly**
- Mendirikan *Model* `DocumentCategory`.
- Mendirikan *Model* `Document` dengan perlindungan mass-assignment ekstra (hanya parameter sah di dalam `$fillable`). `download_count` dilarang keras diisi sembarangan.

**Step 3: Observer Registration**
- Mencetak kelas baru `DocumentObserver`.
- Menyuntikkan perintah penumpasan direktori `Storage::disk('public')->delete()` spesifik pada blok *function* `forceDeleted()` dan deteksi perbedaan nilai fisik pada blok `updated()`.
- Merekatkan *PHP Attribute* `#[ObservedBy()]` ke ujung model `Document`.

**Step 4: Factory & Seeding**
- Membentuk *Factory* dokumen dan menyusun data *dummy*.
- Menulis *Seeder* `DocumentCategorySeeder`.
- Menulis *Seeder* `DocumentSeeder` menggunakan metode anti OOM `User::inRandomOrder()->limit(20)->get()`.

**Step 5: Code Integration & Formatting**
- Mendaftarkan seeder di `DatabaseSeeder.php`.
- Perintah *Artisan*: `migrate` & `db:seed`.
- Perintah standardisasi *PEP/PSR* dari *Pint*: `vendor/bin/pint --format agent`.

**Step 6: Post-Coding Review**
- Penulisan *Verification Report*, *Architecture Review* (jika ada sisa celah), *Refactoring*, dan *Final Validation*.

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| **Storage Leak via File Replace** | (*Critical*) Pengguna mengedit dokumen, menimpa file PDF lama dengan PDF baru. PDF lama tertinggal membusuk di server. **Mitigasi**: Pemasangan hook pada _Event_ `updated()` di dalam Observer untuk menghapus `$model->getOriginal('file_path')`. |
| **Mass File IDOR Enumeration** | (*High*) Publik menebak ID pada URL Unduh. **Mitigasi**: Seluruh pengaksesan berkas direntangkan menggunakan `ULID` 26 karakter unik. |
| **Out-of-Memory (*OOM*) Seeding** | (*Medium*) Memuat koleksi entitas berlebihan di seeder. **Mitigasi**: Instruksi `.limit()` membatasi pengeluaran ke memori RAM lokal. |
| **Index Miss pada Paginasi** | (*Medium*) Basis data kewalahan mengurutkan tabel besar. **Mitigasi**: Membangun komposit b-tree menyertakan `created_at` ke posisi kanan dari klausa spesifik (_Covering Index Layout_). |

## 6. Rollback Strategy
Jika di tengah jalan terdapat anomali fatal:
1. Batalkan registrasi *Seeder* dari `DatabaseSeeder.php`.
2. Kosongkan artefak *Factory*, *Migration*, *Observer*, dan *Model* bawaan M9.
3. Kirim instruksi `php artisan migrate:rollback --step=2`.

## 7. Acceptance Checklist
- [ ] 2 File migrasi ter-deploy berbekal `mime_type` dan `unsignedBigInteger`.
- [ ] 2 File Model dan 2 Factory menjunjung arsitektur *Strict Type*.
- [ ] Indeks tabel *composite* merangkul properti agregasi dan penyusunan `(category_id, is_public, deleted_at, created_at)`.
- [ ] `DocumentObserver` tangguh mengawal `forceDeleted` **sekaligus** merespons mutasi pada `updated`.
- [ ] Seeder berlaku stabil di RAM *(Collections Limited)*.
- [ ] *Exit Code 0* saat pemeriksaan mutasi linting menggunakan Laravel Pint.
