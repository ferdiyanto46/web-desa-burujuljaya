# 📑 Design Document — Module 10 (Complaints & Aspirations)

> **Status**: Draft by Senior Software Architect (Refactored)

## Latar Belakang
Module 10 merupakan modul Pengaduan dan Aspirasi Masyarakat untuk Website Desa Burujuljaya. Masyarakat dapat mengirimkan Pengaduan, Aspirasi, Kritik, maupun Saran. Setiap laporan dibekali sistem pelacakan komprehensif yang mencakup status progres, kategori, multi-lampiran, serta riwayat penanganan (audit trail) oleh petugas yang berwenang.

---

## 1. Tujuan Modul
Modul ini bertujuan menyediakan portal interaktif dua arah antara warga dan Pemerintah Desa yang transparan, aman, dan dapat dilacak. Modul dirancang secara **Enterprise** untuk memastikan keamanan identitas pelapor (*ULID, IDOR Protection*), keutuhan data riwayat penanganan (*History Logging*), jaminan performa tinggi saat lonjakan laporan (*Anti N+1, Composite Indexing*), serta proteksi absolut terhadap penumpukan sampah berkas peladen (*Storage Leak Prevention via Observer*).

---

## 2. Struktur Database & 3. Penjelasan Kolom

### A. Tabel: `complaint_categories` (Lookup Table)
Menyimpan jenis kategori aduan (Pengaduan, Aspirasi, Kritik, Saran, Infrastruktur, dll).

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | NO | Nama kategori pelaporan |
| `slug` | VARCHAR(255) | NO | Identifier URL kategori, `UNIQUE` |
| `is_active` | BOOLEAN | NO | Tampilkan di form pelaporan publik, default `true` |
| `created_at`, `updated_at` | TIMESTAMP | YES | Laravel Timestamps |

### B. Tabel: `complaints` (Main Data)
Menyimpan inti laporan dari masyarakat.

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `ulid` | CHAR(26) | NO | Nomor Tiket Laporan (Anti IDOR), `UNIQUE` |
| `category_id` | BIGINT UNSIGNED | NO | Relasi ke `complaint_categories` |
| `reporter_name` | VARCHAR(255) | NO | Nama pelapor |
| `reporter_email` | VARCHAR(255) | YES | Surel pelapor (untuk notifikasi balasan) |
| `reporter_phone` | VARCHAR(20) | YES | Telepon/WhatsApp pelapor |
| `title` | VARCHAR(255) | NO | Judul Laporan |
| `body` | TEXT | NO | Isi detail laporan/kronologi |
| `status` | VARCHAR(50) | NO | Enum (unread, processing, resolved, rejected) |
| `is_anonymous` | BOOLEAN | NO | Centang jika pelapor minta identitas disamarkan |
| `created_at`, `updated_at` | TIMESTAMP | YES | Laravel Timestamps |
| `deleted_at` | TIMESTAMP | YES | Soft Delete flag |

### C. Tabel: `complaint_attachments` (One-to-Many)
Mengakomodasi bukti fisik. Satu laporan bisa memiliki banyak lampiran foto/dokumen.

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `ulid` | CHAR(26) | NO | Identifier unik pengunduhan (Anti IDOR), `UNIQUE` |
| `complaint_id` | BIGINT UNSIGNED | NO | Relasi induk ke `complaints` |
| `file_path` | VARCHAR(500) | NO | Lokasi fisik direktori berkas |
| `file_type` | VARCHAR(100) | NO | MIME type (image/jpeg, application/pdf) |
| `file_size` | UNSIGNED BIGINT | NO | Ukuran fail dalam satuan Bytes |
| `created_at`, `updated_at` | TIMESTAMP | YES | Laravel Timestamps |

### D. Tabel: `complaint_histories` (Audit Trail)
Merekam setiap pergerakan status laporan (Audit Trail) dan balasan petugas.

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `complaint_id` | BIGINT UNSIGNED | NO | Relasi ke `complaints` yang sedang ditangani |
| `actor_type` | VARCHAR(50) | NO | Aktor pemicu riwayat: `system`, `citizen`, atau `admin` |
| `user_id` | BIGINT UNSIGNED | YES | Petugas/Aparat yang menangani (FK `users`, `nullOnDelete`) |
| `status_from` | VARCHAR(50) | YES | Status sebelum diubah (null jika baru masuk) |
| `status_to` | VARCHAR(50) | NO | Status baru (processing, resolved, dll) |
| `notes` | TEXT | YES | Pesan balasan/catatan penanganan dari aparat/warga |
| `created_at`, `updated_at` | TIMESTAMP | YES | Laravel Timestamps |

---

## 4. Alasan Pemilihan Tipe Data
- **`CHAR(26)` pada `ulid`**: Mengamankan nomor pelacakan tiket aduan publik dan berkas lampirannya (`complaint_attachments`). Melalui *Route Key Binding* ULID, publik tidak akan bisa mengeksploitasi nomor berkas (*Integer Bruteforce/IDOR*).
- **`VARCHAR(20)` pada `reporter_phone`**: Lebar yang pas untuk format internasional (+62...).
- **`UNSIGNED BIGINT` pada `file_size`**: Mencegah *Integer Overflow* dan memastikan ukuran *byte* tidak akan pernah merujuk ke angka negatif.
- **`TEXT` pada `notes` & `body`**: Menampung elaborasi panjang warga dan perangkat desa tanpa batasan 255 karakter VARCHAR.

## 5. Foreign Key Rules
1. `complaints.category_id` -> `complaint_categories.id`
2. `complaint_attachments.complaint_id` -> `complaints.id`
3. `complaint_histories.complaint_id` -> `complaints.id`
4. `complaint_histories.user_id` -> `users.id` (Aparat Penanggap)

## 6. Cascade Rules
- **`restrictOnDelete()` pada Entitas Utama**: Kategori tidak boleh dihapus jika masih ada Laporan di dalamnya. Relasi dari tabel `complaints` menuju `complaint_attachments` dan `complaint_histories` juga dibentengi secara `RESTRICT`. 
- **`nullOnDelete()` pada Aktor Histori**: Relasi `user_id` pada `complaint_histories` menggunakan *Null On Delete*. Jika seorang aparat purna tugas atau dihapus dari sistem, catatan historikal pekerjaannya tetap eksis namun merujuk kepada relasi kosong (NULL). Hal ini ditopang kolom `actor_type` yang tetap melestarikan jejak tipe penanggap (*admin/system*).
- Kebuntuan perlindungan hapus permanen (`RESTRICT`) saat *forceDelete* laporan akan diselesaikan pada level Eloquent secara mulus melalui integrasi **ComplaintObserver**.

## 7. Index Strategy (Optimasi Pola Kueri Admin Dasbor)
- **`complaints` Index 1**: `(status, deleted_at, created_at)`
- **`complaints` Index 2**: `(category_id, deleted_at, created_at)`
  *Alasan Arsitektural*: Admin Dasbor desa lazimnya menyaring data menggunakan filter ganda atau tunggal: *"Tampilkan semua laporan Unread (status)"* atau *"Tampilkan semua aduan Infrastruktur (category_id)"* yang diurutkan berdasar yang terbaru. Jika menggunakan *single composite index* beranggotakan 4 elemen sekaligus, *optimizer database* (Left-Most Prefix Rule) akan mengabaikan saringan kategori jika filter status kosong. Strategi dua buah indeks 3-kolom ini dipastikan selalu *Hit* berapapun kombinasi filter kuerinya.
- **`complaints` & `complaint_attachments` Index**: `ulid` untuk pencarian cek tiket publik & pengunduhan berkas rahasia.
- **`complaint_histories` Index**: `(complaint_id, created_at)` untuk memuat benang merah kronologi secepat kilat.

## 8. Model Design
- Semua entitas dideklarasikan dengan `declare(strict_types=1);` dan menggunakan pilar `final class`.
- Enkapsulasi `explicit $fillable`: Field rawan seperti `status` pada tabel *complaints* **dilarang keras** disusupkan pada _$fillable_ untuk menghindar dari injeksi mass-assignment (OWASP).
- **Casting (`casts()`)**: 
  - `is_anonymous` di-_cast_ ke `boolean`.
  - `file_size` di-_cast_ ke `integer`.
- **SoftDeletes**: Hanya diaktifkan pada induk tabel `Complaint`. Tabel relasi bawahan (*Attachments*, *Histories*) cukup dikendalikan oleh relasi Eloquent Induk tanpa perlu soft-delete beruntun guna mengefisienkan kueri.
- **HasUlid**: Tertanam pada entitas induk `Complaint` dan *child* `ComplaintAttachment`. (Diset sebagai *Route Key* di dalam lampiran).

## 9. Query Scope
- `scopeUnread(Builder $query)`: Mengerucutkan Laporan berstatus 'unread'.
- `scopeActiveCategory(Builder $query)`: Di dalam model Kategori.
- `scopeWithDetails(Builder $query)`: Melakukan _Eager Loading_ konstan `['category', 'attachments', 'histories.user']` untuk pemusnahan mutlak problem N+1.

## 10. Observer Design
Terdapat dua formasi *Observer* yang bekerja secara orkestra:
- **`ComplaintAttachmentObserver`**:
  - **`forceDeleted()`**: Mengeksekusi penarikan memori fail `Storage::delete($attachment->file_path)`.
  - **`updated()`**: Berperan sebagai jaring pengaman (*Safety Net*). Pada *Clean Architecture Service Layer*, fail lampiran seharusnya diperlakukan secara *Immutable* (tidak bisa diganti/diperbarui, hanya boleh ditambah baru atau dihapus). Namun, Observer ini tetap disiagakan untuk menyapu fail lama bila fungsi *update* tak terhindarkan terjadi.
  - Memanfaatkan `public bool $afterCommit = true;` menunda I/O disk hingga transaksi sukses.
- **`ComplaintObserver`**:
  - **`forceDeleting()`**: Bertugas melerai benturan konstrain `RESTRICT` pada pangkalan data. Ketika laporan hendak dihapus absolut, *Observer* secara mandiri akan memutar iterasi *(looping)* dan memanggil eksekusi penghancuran per fail lampiran: `$complaint->attachments()->cursor()->each(fn($att) => $att->forceDelete());`. Proses ini akan meletupkan `ComplaintAttachmentObserver` per data, menghapus jejak memori secara konsisten dan melegalkan *force delete* pada tabel induk tanpa memancing penolakan SQL Exception.

## 11. Factory Strategy
- **`ComplaintFactory`**: Mensimulasikan nama pelapor lokal, judul keluhan jalan/infrastruktur, *body*, serta properti `is_anonymous` acak.
- **`ComplaintAttachmentFactory`**: Memalsukan unggahan bukti (*image/jpeg*) ke direktori abstraksi acak.
- Pemanfaatan konvensi iteratif `->recycle()` sangat dijunjung untuk pengefisienan RAM fabrikasi data.

## 12. Seeder Strategy
- Menerapkan batasan kewajaran produksi data `limit(10)` pada relasi *Users* dan pembentukan *Factory* secukupnya pada `ComplaintSeeder` (misal maksimal memicu 50 data aduan) untuk mencegah *Memory Limit Exhaustion (OOM)* pada masa pengembangan lokal (*development*).
- Membuat hierarki cerdas: Seed Categories -> Seed Complaints -> Seed Histories (dengan `actor_type`) -> Seed Attachments.

## 13. Security Review (OWASP Top 10)
- **IDOR Protection (A01: Broken Access Control)**: Penggunaan *Primary Key* tipe Ulid membantahkan metode tebak tiket (baik aduan maupun rute lampirannya). 
- **Mass Assignment Vulnerability (A08)**: Larangan keras meletakkan `status` di `$fillable`. Pembaruan status hanya dilakukan via operasi kaku di *Service Layer* (*Admin Privileges*).

## 14. Performance Review
- **Anti N+1**: Dipecahkan oleh kombinasi cemerlang *Query Scopes* dan struktur model standar.
- **Storage Leak Prevention**: Dieksekusi otomatis oleh *Observer* berbekal _delay transaction afterCommit_, meyakinkan bahwa 10 ribu fail aduan yang terhapus *(force delete)* tidak akan tertinggal menjadi limbah diska.

## 15. Laravel Best Practice
- Kepatuhan **Laravel 13** & **PHP 8.3**.
- Atribut dekorator `#[ObservedBy()]` dipasang mandiri di tubuh Kelas Model.
- Konvensi penamaan leksikal (_linter_) dijaga murni oleh standar **Pint (Agent)**.
- Arsitektur dipilah (_Separation of Concern_); *Model* mengemban validasi data, *Observer* mengeksekusi memori diska (I/O).

## 16. Daftar File yang Akan Dibuat
1. `database/migrations/2026_07_24_100000_create_complaint_categories_table.php`
2. `database/migrations/2026_07_24_100010_create_complaints_table.php`
3. `database/migrations/2026_07_24_100020_create_complaint_attachments_table.php`
4. `database/migrations/2026_07_24_100030_create_complaint_histories_table.php`
5. `app/Models/ComplaintCategory.php`
6. `app/Models/Complaint.php`
7. `app/Models/ComplaintAttachment.php`
8. `app/Models/ComplaintHistory.php`
9. `app/Observers/ComplaintObserver.php`
10. `app/Observers/ComplaintAttachmentObserver.php`
11. `database/factories/ComplaintCategoryFactory.php`
12. `database/factories/ComplaintFactory.php`
13. `database/factories/ComplaintAttachmentFactory.php`
14. `database/factories/ComplaintHistoryFactory.php`
15. `database/seeders/ComplaintCategorySeeder.php`
16. `database/seeders/ComplaintSeeder.php`
- Modifikasi injeksi kelas Seeder ke `database/seeders/DatabaseSeeder.php`.
