# 📑 Design Document — Module 8 (Statistics)

## 1. Tujuan Modul
Modul **Statistics** bertujuan untuk menyediakan sistem penyimpanan dan manajemen data statistik kependudukan atau demografi desa (seperti data pendidikan, pekerjaan, agama, rentang usia, dll). Data ini akan disajikan kepada publik dalam bentuk grafik atau angka pada *frontend*. Modul ini dirancang seringan mungkin tanpa menggunakan UUID maupun Soft Delete karena karakteristik datanya yang murni agregat dan transaksional sederhana.

## 2. Struktur Tabel & Penjelasan Kolom

### Tabel: `statistic_categories` (Lookup Table)
Digunakan untuk mengelompokkan jenis statistik (misal: "Statistik Pekerjaan", "Statistik Pendidikan").

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | NO | Nama kategori (mis. "Agama") |
| `slug` | VARCHAR(255) | NO | URL-friendly identifier, `UNIQUE` |
| `is_active` | BOOLEAN | NO | Flag untuk menyembunyikan kategori dari publik, default `true` |
| `created_at` | TIMESTAMP | YES | Laravel Timestamps |
| `updated_at` | TIMESTAMP | YES | Laravel Timestamps |

### Tabel: `village_statistics` (Main Data)
Menyimpan indikator data untuk tiap kategori berdasarkan tahun.

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `category_id` | BIGINT UNSIGNED | NO | Foreign Key ke `statistic_categories` |
| `name` | VARCHAR(255) | NO | Nama item statistik (mis. "Islam", "S1", "Petani") |
| `value` | BIGINT UNSIGNED | NO | Nilai metrik (jumlah jiwa/kuantitas) |
| `year` | SMALLINT UNSIGNED | NO | Tahun pencatatan statistik (mis. 2026) |
| `created_at` | TIMESTAMP | YES | Laravel Timestamps |
| `updated_at` | TIMESTAMP | YES | Laravel Timestamps |

## 3. Alasan Pemilihan Tipe Data
- `BIGINT UNSIGNED` pada `value`: Mengantisipasi angka metrik kependudukan/keuangan yang mungkin membesar seiring waktu.
- `SMALLINT UNSIGNED` pada `year`: Lebih hemat memori dibandingkan *Integer* utuh untuk sekadar menampung format 4-digit tahun (maksimum nilai 65535, aman dari batasan 2038 *bug* atau *Integer overflow* biasa).
- Tidak menggunakan `UUID`: Sesuai panduan *Master Database Design*, data agregat statistik umumnya tidak diakses satuan secara spesifik melalui REST API publik, melainkan ditarik secara massal (berdasarkan Kategori/Tahun), sehingga *Surrogate PK Integer* dirasa cukup efisien.

## 4. Foreign Key Rules
- `village_statistics.category_id` -> `statistic_categories.id`.
  
## 5. Cascade Rules
- Konstrain untuk `category_id` diterapkan dengan **`restrictOnDelete()`**. 
- Kategori statistik tidak boleh terhapus jika di dalamnya masih tersimpan data `village_statistics`. Hal ini mempertahankan integritas *orphan-free*.

## 6. Index Strategy
- **Composite Index** `(category_id, year)` pada `village_statistics`. Pola *query* paling umum di modul ini adalah, "Tampilkan seluruh data statistik pekerjaan (Kategori X) pada tahun 2026 (Tahun Y)". Komposit ini menjamin kecepatan luar biasa untuk *query* tersebut.
- **Index tunggal** `(slug)` pada tabel kategori untuk mempercepat *query route-binding* atau pencarian label.

## 7. Model Design
- `StatisticCategory` dan `VillageStatistic` akan diwujudkan sebagai `final class` yang mengimplementasikan `strict_types=1`.
- **Tidak ada penggunaan Trait** `HasUlid`, `HasUniqueSlug`, maupun `SoftDeletes` berdasarkan cetak biru *Master Database Design*.

## 8. Scope Query
- `scopeActive()` pada model `StatisticCategory` untuk mem-filter `is_active = true`.
- `scopeByYear(int $year)` pada model `VillageStatistic` untuk melokalisir kueri data berdasarkan tahun spesifik.

## 9. Observer
- Modul ini murni penyimpan deret angka (tanpa integrasi *file storage/image*), sehingga **tidak membutuhkan** Observer. Pembersihan atau perubahan *business logic* cukup diletakkan di ranah *Service Layer*.

## 10. Factory Strategy
- **`StatisticCategoryFactory`**: Men-generate kategori acak berbasis kata (Word).
- **`VillageStatisticFactory`**: Men-generate rentang nilai acak `value` (mis. 0-500) dan menugaskan `year` antara 3 tahun ke belakang (mis. 2024, 2025, 2026).

## 11. Seeder Strategy
- **Seeder Definitif**: Menginjeksi nama-nama kategori absolut seperti: "Pendidikan", "Pekerjaan", "Agama", "Usia" pada `StatisticCategorySeeder`.
- Melakukan rotasi dengan metode `recycle()` untuk menetapkan puluhan nilai dummy ke `village_statistics` secara idempoten dan mengantisipasi *Memory Limit*.

## 12. Security Review
- Ketiadaan *Soft Delete* artinya penghapusan akan berjalan mutlak dari dalam *database*. Otentikasi lapis *Admin (Authorization)* wajib dipastikan kuat dari ujung REST/Controller.
- FK `RESTRICT` menangkal peluang anomali *Broken Access Control* atau malfungsi data bila kategori terhapus paksa.

## 13. Performance Review
- Modul sangat ringan akibat ketiadaan _SoftDelete_ (tak ada muatan kondisi *where null deleted_at* yang berlebih).
- *Composite Index* (`category_id`, `year`) akan mengoptimalkan eksekusi agregasi dan visualisasi *charting/graph* di sisi antarmuka.

## 14. Laravel Best Practice
- Penggunaan `explicit $fillable`.
- Metode `casts()` khusus tipe (mis. casting `value` ke `integer`, `is_active` ke `boolean`).
- Isolasi relasi Eloquent (`hasMany` dan `belongsTo`) di dalam kelas model final.

## 15. Daftar File yang Akan Dibuat
1. `database/migrations/2026_07_24_081000_create_statistic_categories_table.php`
2. `database/migrations/2026_07_24_081010_create_village_statistics_table.php`
3. `app/Models/StatisticCategory.php`
4. `app/Models/VillageStatistic.php`
5. `database/factories/StatisticCategoryFactory.php`
6. `database/factories/VillageStatisticFactory.php`
7. `database/seeders/StatisticCategorySeeder.php`
8. `database/seeders/VillageStatisticSeeder.php`
- `DatabaseSeeder.php` akan dimodifikasi (*Modify*) untuk mendaftarkan kelas seeder ini.
