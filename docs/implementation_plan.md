# Module 3 — Implementation Plan

## 1. Objective
Mengimplementasikan fungsionalitas manajemen berita (News Management) desa untuk sistem utama yang meliputi pembuatan kategori berita, penulisan artikel, dan status penerbitan. Modul ini menjadi krusial sebagai media informasi resmi yang menghubungkan aparat desa dengan masyarakat. Implementasi ditujukan untuk mencapai standarisasi arsitektur sesuai dengan standar Laravel 13, performa query yang tinggi, serta memastikan keamanan data (terhindar dari IDOR, Mass Assignment, dan ancaman lain).

## 2. Scope
Berikut adalah daftar file yang tercakup dalam pengerjaan Module 3:

**CREATE:**
- `app/Enums/ArticleStatus.php`
- `database/migrations/2026_07_23_171336_create_article_categories_table.php`
- `database/migrations/2026_07_23_171349_create_articles_table.php`
- `app/Models/ArticleCategory.php`
- `app/Models/Article.php`
- `database/factories/ArticleCategoryFactory.php`
- `database/factories/ArticleFactory.php`
- `database/seeders/ArticleCategorySeeder.php`
- `database/seeders/ArticleSeeder.php`

**MODIFY:**
- `database/seeders/DatabaseSeeder.php`

**DELETE:**
- Tidak ada file existing yang dihapus.

## 3. Dependency Check
- **Module 1 (Users & RBAC)**: Sangat bergantung. Model `Article` membutuhkan foreign key `user_id` yang merujuk pada tabel `users` (penulis/author). Harus mempertimbangkan _soft delete_ pada user.
- **Module 2 (Foundation)**: Independen secara data, namun mengikuti pola arsitektur *clean architecture* yang telah disepakati dari implementasi dan _review_ Module 1 & Module 2 (contoh: final class, strict typing, dsb).
- **Package yang digunakan**: `illuminate/support` (untuk fitur `Str::ulid` dan `Str::slug`), Enum Native PHP 8.1+.
- **Database yang diperlukan**: Tabel `users` yang telah tersedia dengan skema dari M1. Sistem database MySQL/MariaDB yang mendukung operasi string parsing dan indexing FULLTEXT.

## 4. Execution Order
Implementasi akan dilakukan secara sistematis dengan urutan sebagai berikut:

- **Step 1 - Enum Setup**: Pembuatan class `App\Enums\ArticleStatus` untuk pembatasan opsi *Draft*, *Published*, *Scheduled*, dan *Archived*.
- **Step 2 - Migrations**: Pembuatan skema database melalui migration, dimulai dari struktur lookup `article_categories` baru disusul dengan pembuatan skema `articles`.
- **Step 3 - Models**: Membangun relasi (author, category), auto-generator slug & ULID via Boot event (Observer logic), explicit `$fillable`, *type casting*, dan penerapan Query Scopes (published, draft, featured, dll).
- **Step 4 - Factories**: Pendefinisian Dummy data yang relevan dengan beragam logic (State) sehingga memudahkan *testing* dan *seeding*.
- **Step 5 - Seeders**: Membuat Seeder mandiri (`ArticleCategorySeeder` & `ArticleSeeder`) dan meregisternya di `DatabaseSeeder.php` agar dapat dipanggil massal.
- **Step 6 - Tests** (Opsional/Sesuai instruksi): Mengkonfirmasi kelayakan relasi atau scope via PHPUnit / Pest.
- **Step 7 - Run Pint**: Memformat keseluruhan perubahan menggunakan `vendor/bin/pint --format agent` untuk menjaga gaya PSR-12/Laravel Pint.
- **Step 8 - Self Review**: Verifikasi internal terhadap konsistensi kode (cek strict type, foreign keys, *return type*, scope name, dan relasi).

## 5. Risks
- **Risiko Slug Duplikat**: Jika terdapat pembuatan artikel secara serentak (concurrency) dengan judul identik, dapat berpotensi error `UNIQUE constraint`.
  - **Mitigasi**: Menerapkan looping incremental (`slug-1`, `slug-2`) dipadu dengan cek ketersediaan data termasuk status `trashed` pada saat `Model::creating` / `Model::updating`.
- **Risiko Orphaned Data**: Artikel menjadi tidak memiliki referensi kategori atau user jika relasinya terhapus.
  - **Mitigasi**: Menggunakan klausa `restrictOnDelete()` pada constraint foreign key di layer migration. Memaksakan relasi yang valid.

## 6. Rollback Strategy
Apabila dalam eksekusi terjadi kegagalan atau _bug_ kritikal yang lolos ke review:
1. Jalankan `php artisan migrate:rollback --step=1` (atau 2) untuk mereset perubahan database tabel `articles` dan `article_categories`.
2. Hapus seluruh file *CREATE* yang didaftarkan pada _Scope_.
3. Batalkan/revert perubahan pada `DatabaseSeeder.php` dengan cara meng-undo blok kode M3.

## 7. Acceptance Checklist
- [ ] Skema database tercipta dengan akurat (foreign keys *restrict*, FULLTEXT index, *composite index*).
- [ ] `ArticleStatus` tersedia dan me-return *Badge Color* beserta *Label* IDN yang sesuai.
- [ ] `Article` model memproduksi *ULID* otomatis apabila diinisiasi tanpa ULID.
- [ ] `Article` model memproduksi *Slug unik* otomatis ketika *Title* tersimpan.
- [ ] Tersedia Query Scope: `published`, `draft`, `scheduled`, `featured`, `search`.
- [ ] Seeder bekerja secara penuh di-invoke melalui `php artisan db:seed`.
- [ ] Standard formatting (Pint) *passed*.
