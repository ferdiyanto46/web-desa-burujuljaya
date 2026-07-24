# Module 4 — Implementation Plan

## 1. Objective
Mengimplementasikan modul **Announcements (Pengumuman)** dan **Agendas (Agenda)** untuk Website Desa Burujuljaya. Implementasi ini mencakup pembuatan skema database, model, factory, dan seeder yang telah dioptimasi serta terintegrasi dengan struktur keamanan, performa, dan arsitektur *clean code* (memperbaiki DRY violation) sesuai hasil *Architecture Review*.

## 2. Scope
Berikut adalah daftar file yang akan diproses selama implementasi:

**A. Refactoring (Pre-requisite / DRY Compliance)**
- **CREATE**: `app/Traits/HasUlid.php`
- **CREATE**: `app/Traits/HasUniqueSlug.php`
- **MODIFY**: `app/Models/Article.php` (Ganti *logic boot* dengan Traits)
- **MODIFY**: `app/Models/ArticleCategory.php` (Ganti *logic boot* dengan Traits)

**B. Module 4 Core**
- **CREATE**: `database/migrations/xxxx_create_announcements_table.php`
- **CREATE**: `database/migrations/xxxx_create_agendas_table.php`
- **CREATE**: `app/Models/Announcement.php`
- **CREATE**: `app/Models/Agenda.php`
- **CREATE**: `database/factories/AnnouncementFactory.php`
- **CREATE**: `database/factories/AgendaFactory.php`
- **CREATE**: `database/seeders/AnnouncementSeeder.php`
- **CREATE**: `database/seeders/AgendaSeeder.php`

**C. Integrasi**
- **MODIFY**: `database/seeders/DatabaseSeeder.php` (Mendaftarkan Seeder M4)
- **NOTE**: M4 akan me-reuse `App\Enums\ArticleStatus` sebagai *Shared Enum* (tidak membuat Enum baru untuk mematuhi prinsip KISS).

## 3. Dependency Check
- **Module 1 (Users)**: Mengharuskan tabel `users` eksis karena kolom `user_id` pada pengumuman dan agenda di-set sebagai *Foreign Key* (`restrictOnDelete`).
- **Module 3 (News)**: Bergantung pada keberadaan `ArticleStatus` (Enum). Jika M3 tidak ada, enum tersebut harus direplikasi atau digeneralisasi.

## 4. Execution Order

**Step 1: Arsitektural Refactoring (Traits Extraction)**
- Membuat trait `HasUlid` dan `HasUniqueSlug`.
- Membersihkan redundansi kode pada M3 (`Article` dan `ArticleCategory`).

**Step 2: Database Migration**
- Membuat file migrasi untuk `announcements` dan `agendas`.
- Menerapkan rekomendasi *Architecture Review*: 
  - Index optimal untuk pengumuman: `(status, deleted_at, is_pinned, published_at)`
  - Index optimal untuk agenda: `(status, deleted_at, start_datetime)`
  - Fulltext index: `(title, excerpt, content)` pada announcements.

**Step 3: Models Construction**
- Menerapkan `strict_types`, `$fillable` eksplisit, tipe data casts, dan Enum binding.
- Memanggil *Traits* `HasUlid` dan `HasUniqueSlug`.
- Meng-override `getRouteKeyName()` untuk mengembalikan `'ulid'` (mencegah IDOR).
- Membuat *Query Scope* `scopeWithDetails()` untuk menghindari N+1 query.

**Step 4: Factories Development**
- Menyiapkan *state* khusus (Published, Draft, Scheduled, Pinned, Expired) agar data uji komprehensif.

**Step 5: Seeders Execution**
- Mem-build seeder untuk meng-_generate_ 10 pengumuman realistis dan 10 agenda (kombinasi masa lalu dan masa depan).
- Menyatukan M4 ke dalam `DatabaseSeeder`.

**Step 6: Formatting & Validation**
- Menjalankan perintah `php artisan migrate:fresh --seed`.
- Menjalankan `vendor/bin/pint --format agent`.

**Step 7: Self Review**
- Mengecek kesesuaian foreign keys, index, tipe data, dan konvensi *Clean Architecture*.

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| Refactoring M3 (Traits) menyebabkan aplikasi bentrok (Breaking Change). | Eksekusi refactoring dilakukan dengan isolasi *copy-paste* presisi. *Automated tests* atau seeder akan mendeteksi langsung jika fitur slug/ULID M3 rusak. |
| Inkonsistensi Timezone pada Agenda `start_datetime`. | Factory akan di-set secara deterministik pada offset jam lokal (`Carbon::now()`). Tidak akan menggunakan *raw integer timestamp* di query builder. |

## 6. Rollback Strategy
Jika implementasi M4 gagal atau mengacaukan skema sistem:
1. Batalkan perubahan pada file M3 (`Article`, `ArticleCategory`).
2. Hapus file Traits yang baru dibuat.
3. Hapus seluruh file migration, model, dan seeder yang memiliki relasi dengan `announcements` dan `agendas`.
4. Kembalikan `DatabaseSeeder` ke versi semula (hanya me-load M1, M2, M3).
5. Jalankan kembali `php artisan migrate:fresh --seed`.

## 7. Acceptance Checklist
- [ ] Traits `HasUlid` dan `HasUniqueSlug` dibuat dan diimplementasikan secara global.
- [ ] Tabel `announcements` memiliki *composite index* optimal yang direkomendasikan.
- [ ] Tabel `agendas` berhasil ter-relasi ke `users`.
- [ ] Model menggunakan `ArticleStatus` (atau enum general) tanpa membuat duplikasi Enum.
- [ ] Seluruh kelas mematuhi aturan Laravel 13, *Strict Types*, dan PSR-12 (Pint).
- [ ] Dummy data pengumuman (termasuk pinned) dan agenda (mendatang & lewat) berhasil ter-seed secara otomatis.
