# 📑 Design Document — Module 9 (Documents)

> **Status**: *Refined by Senior Architect Review*

## 1. Tujuan Modul
Modul **Documents (Unduhan & Regulasi)** bertujuan menyediakan fasilitas repositori sentral bagi pemerintah desa dalam mempublikasikan dan menyimpan berkas digital (PDF, DOCX, Excel, dll). Modul ini mengakomodasi dokumen publik maupun privat/internal. Entitas utamanya diproteksi dengan pola *Soft Delete*, pengidentifikasi eksternal `ULID`, penghitung jumlah unduhan, dan dilengkapi dengan *Observer* khusus guna menghindari penumpukan berkas tak bertuan (*Storage Leak*) baik saat penghapusan permanen maupun saat berkas diperbarui (di-*replace*).

## 2. Struktur Tabel & Penjelasan Kolom

### Tabel: `document_categories` (Lookup Table)
Pengelompokan jenis dokumen (misal: "Peraturan Desa", "Laporan Keuangan", "Formulir Pelayanan").

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | NO | Nama kategori |
| `slug` | VARCHAR(255) | NO | URL-friendly identifier, `UNIQUE` |
| `is_active` | BOOLEAN | NO | Tampilkan kategori di publik, default `true` |
| `created_at`, `updated_at` | TIMESTAMP | YES | Waktu pembuatan & pembaruan |

### Tabel: `documents` (Main Data)
Menyimpan riwayat tunggal suatu berkas digital.

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `ulid` | CHAR(26) | NO | Identifier publik (Cegah IDOR), `UNIQUE` |
| `user_id` | BIGINT UNSIGNED | NO | Pembuat dokumen (Uploader) |
| `category_id` | BIGINT UNSIGNED | NO | Relasi kategori dokumen |
| `title` | VARCHAR(255) | NO | Judul dokumen |
| `slug` | VARCHAR(255) | NO | URL identifier dokumen, `UNIQUE` |
| `description` | TEXT | YES | Deskripsi singkat atau abstrak isi berkas |
| `file_path` | VARCHAR(500) | NO | Lokasi relatif/absolut penyimpanan berkas |
| `file_extension` | VARCHAR(10) | NO | Denormalisasi: Ekstensi (*pdf, docx*) untuk ikon UI |
| `mime_type` | VARCHAR(100) | NO | Tipe MIME (*application/pdf*) krusial untuk HTTP Header unduhan |
| `file_size` | UNSIGNED BIGINT | NO | Ukuran berkas dalam Bytes |
| `download_count` | UNSIGNED BIGINT | NO | Denormalisasi: Jumlah unduhan, default `0` |
| `is_public` | BOOLEAN | NO | Akses publik vs privat, default `true` |
| `created_at`, `updated_at` | TIMESTAMP | YES | Laravel Timestamps |
| `deleted_at` | TIMESTAMP | YES | Sinyal Soft Delete |

## 3. Alasan Pemilihan Tipe Data
- `CHAR(26)` pada `ulid`: Melindungi _integer PK_ saat pengguna mengakses _endpoint_ _download_ secara anonim.
- `VARCHAR(100)` pada `mime_type`: Sangat krusial dalam *Clean Architecture* web. Ketimbang menebak ekstensi saat proses *download*, aplikasi cukup melempar `mime_type` asli yang tercatat ke *HTTP Response Header* sehingga keamanan transmisi *browser* lebih solid.
- `UNSIGNED BIGINT` pada `file_size` & `download_count`: Mengamankan nilai tak wajar (negatif) serta kebal terhadap ancaman _Integer Overflow_.

## 4. Foreign Key Rules
- `documents.user_id` -> `users.id` (Aktor Pengunggah).
- `documents.category_id` -> `document_categories.id` (Grup Dokumen).

## 5. Cascade Rules
- Ditetapkan sepenuhnya sebagai **`restrictOnDelete()`**. 
- Seorang *User* tidak dapat dihapus mutlak jika masih terikat sebagai pengunggah `document`. Hal yang sama berlaku ketat untuk `category_id`. Mempertahankan integritas data dari lubang (*Orphan Data*).

## 6. Index Strategy
- **Composite Index Utama**: `(category_id, is_public, deleted_at, created_at)` dalam tabel `documents`. Sangat krusial karena seringkali halaman publik menarik *"Daftar Dokumen Kategori X yang Aktif dan diurutkan berdasarkan Terbaru"*. Susunan indeks b-tree ini akan menutupi seluruh kebutuhan *query* tersebut dalam satu tembakan (*Covering Index-like behavior*).
- **Single Index**: `ulid` untuk operasi _Retrieve_ saat memproses klik _Download_.

## 7. Model Design
- `DocumentCategory` dan `Document` disusun menggunakan `final class` (mencegah *inheritance* rancu) berkalang tipe solid `strict_types=1`.
- **Integrasi Trait**: Entitas sentral `Document` mewarisi trah `HasUlid`, `HasUniqueSlug`, dan `SoftDeletes`.
- Penggunaan `explicit $fillable` ketat. Variabel krusial seperti `download_count` dilarang keras dimasukkan ke array *$fillable* untuk mencegah mutasi curang (*Mass Assignment Vulnerability*).

## 8. Query Scopes
- `scopePublic(Builder $query)`: Menyaring parameter dokumen di mana `is_public` = `true`.
- `scopeWithDetails(Builder $query)`: Menginjeksi *Eager Loading* `['category', 'user']`.

## 9. Observer
- **`DocumentObserver`** mutlak diwajibkan untuk menambal kelemahan *Storage Leak*.
- **`forceDeleted()` event**: Mencabut berkas fisik permanen (`Storage::delete($document->file_path)`).
- **`updated()` event**: Mengatasi pembaruan berkas (*file replacement*). Jika berkas diganti (`$document->wasChanged('file_path')`), maka berkas lama harus segera dihapus (`Storage::delete($document->getOriginal('file_path'))`). Tanpa event `updated` ini, berkas lama akan menjadi sampah yatim.

## 10. Factory Strategy
- **`DocumentCategoryFactory`**: Men-generate pengelompokan lazim pedesaan (*APBDES*, *SK Kades*, *RPJMDes*).
- **`DocumentFactory`**: Mensimulasikan *title*, rentang ukuran `file_size` acak di kisaran (1024 - 500000 bytes), menetapkan `file_extension` (pdf) dan `mime_type` (application/pdf).

## 11. Seeder Strategy
- Memanggil `DocumentCategorySeeder` dengan nilai absolut.
- Menjalankan relasi iteratif `.recycle()` terhadap seluruh tabel User dan Kategori di dalam `DocumentSeeder`. Menggunakan parameter pembatas `.limit()` untuk kelonggaran *Memory RAM Limit Exhaustion*.

## 12. Security Review
- Ketersediaan parameter `is_public` memungkinkan Kepala Desa / Perangkat menyimpan regulasi internal yang terisolasi dari *Front-end*.
- Penyamaran *Primary ID* murni lewat URL ULID, membentengi sistem dari kerentanan *Mass File IDOR Enumeration* (Sesuai panduan **OWASP Top 10 - Broken Access Control**).
- `mime_type` dicatat langsung menghalangi serangan injeksi berkas palsu karena Header tipe data dikunci dari *Database*.

## 13. Performance Review
- **Anti N+1 Queries**: Terlindungi secara fundamental via `scopeWithDetails`.
- **Row-Lock Contention Mitigation**: Peningkatan *download_count* bisa memicu konflik kunci *database*. Arsitek menyarankan penggunaan eksekusi asinkron (*Queued Jobs* / *Redis*) untuk me-*increment* nilai hitungan di atas apabila trafik sangat padat. Namun, pada level desa, pemanggilan SQL Update mentah masih dimaklumi.

## 14. Laravel Best Practice
- Observasi Model diringkas secara elegan dalam *attribute* PHP 8 `#[ObservedBy()]`.
- Mengonversi data numerik menjadi bawaannya dengan fasilitas `casts()` (meng-_cast_ `is_public` menjadi boolean, `file_size` dan `download_count` menjadi *integer* murni).

## 15. Daftar File yang Akan Dibuat
1. `database/migrations/2026_07_24_091000_create_document_categories_table.php`
2. `database/migrations/2026_07_24_091010_create_documents_table.php`
3. `app/Models/DocumentCategory.php`
4. `app/Models/Document.php`
5. `app/Observers/DocumentObserver.php`
6. `database/factories/DocumentCategoryFactory.php`
7. `database/factories/DocumentFactory.php`
8. `database/seeders/DocumentCategorySeeder.php`
9. `database/seeders/DocumentSeeder.php`
- Modifikasi *array* daftar *Seeders* dalam pustaka `DatabaseSeeder.php`.
