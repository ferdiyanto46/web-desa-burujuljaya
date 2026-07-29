# 📝 Implementation Plan — Module 8 (Statistics)

## 1. Objective
Melakukan implementasi arsitektur Model dan _Database_ untuk fitur **Statistik Desa**, yang akan menampung data agregat demografi publik. Modul ini difokuskan pada ketajaman struktur (Ringan, Tanpa UUID, Tanpa SoftDelete, dan Kuat pada _Composite Indexing_) guna mengamankan performa *query* masif dari tampilan grafis *dashboard*.

## 2. Scope
- **2 Migrasi Database**: `statistic_categories` dan `village_statistics` (berurutan dengan FK `RESTRICT`).
- **2 Kelas Model**: `StatisticCategory` dan `VillageStatistic` (*final class, strict_types=1*).
- **2 Factories**: Pembuat data acak kategori dan populasi agregat angka.
- **2 Seeders**: Menanamkan *lookup* baku (Pendidikan, Pekerjaan, Agama, Usia) secara idempoten dan menggeneralisasi nilai populasinya ke tabel referensi.
- **Modifikasi DatabaseSeeder.php**: Registrasi hirarki eksekusi *seeder* M8.
- *Catatan: Modul ini tidak menggunakan Observer maupun Traits `HasUlid`/`HasUniqueSlug`.*

## 3. Dependency Check
Modul M8 berstatus **Mandiri (Standalone)** secara fungsional untuk tahap fundamental ini, dengan syarat:
- Tidak ada kebergantungan relasi `user_id`. Modul ini tidak mencatat *Author*.
- *Database Engine* harus mendukung komposit Indeks.

## 4. Execution Order

**Step 1: Migrations**
- Mengeksekusi pembuatan skema `statistic_categories`.
- Mengeksekusi pembuatan skema `village_statistics` (berelasi ke kategori) diikuti peresmian `Composite Index (category_id, year)`.

**Step 2: Models**
- Mendesain struktur *fillable* `StatisticCategory`.
- Mendesain struktur *fillable* `VillageStatistic`.
- Mengimplementasikan `casts()` (integer pada nilai jumlah dan boolean pada is_active) beserta *method* Relasi (*hasMany / belongsTo*).
- Mengimplementasikan `scopeActive()` dan `scopeByYear()`.

**Step 3: Factory & Seeders**
- Pembuatan Factory generik untuk *dummy generation*.
- Menyuntikkan _array_ konkrit kategori pada `StatisticCategorySeeder`.
- Menyuntikkan relasi angka menggunakan *method* iterasi `->recycle()` pada `VillageStatisticSeeder`.

**Step 4: Refinement & Formatting**
- Mendaftarkan seeder di `DatabaseSeeder.php`.
- Menjalankan perintah migrasi dan injeksi seeder.
- Menerapkan format standardisasi kode melalui instruksi *Laravel Pint* (`vendor/bin/pint --format agent`).

**Step 5: Post-Coding Review (Dilakukan setelah coding)**
- Pembuatan Verification Report.
- Proses Audit Arsitektur & Performa (Architecture Review).
- Refactoring (Bila diperlukan).
- Validasi akhir.

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| **Data Integrity Constraint (Orphan Rows)** | Upaya paksa penghapusan baris kategori akan membuat tabel turunan _error_. **Mitigasi**: Pemasangan kunci kaku `restrictOnDelete()` pada struktur *Foreign Key* migrasi. |
| **Nilai Minus pada Agregat** | Angka populasi keliru. **Mitigasi**: Menanamkan tipe `unsignedBigInteger` pada kolom nilai nominal metrik populasi. |
| **Integer Overflow pada Tahun** | **Mitigasi**: Digunakannya spesifikasi memori efisien `unsignedSmallInteger` (mampu menampung nilai s/d 65535, aman untuk kolom _year_ standar). |

## 6. Rollback Strategy
Apabila terjadi _Force Majeure_ yang merusak struktur saat perancangan:
1. Hapus kelas Seeder dari array `DatabaseSeeder.php`.
2. Hapus berkas migrasi, model, dan *factory* yang baru dibuat secara utuh.
3. Eksekusi `php artisan migrate:rollback --step=2` untuk membalikkan *state* migrasi terakhir dari M8 tanpa menyentuh tumpukan M7 ke bawah.

## 7. Acceptance Checklist
- [ ] 2 File migrasi ter-deploy bebas bentrok `Foreign Key`.
- [ ] 2 File Model dan 2 Factory berdiri (memakai deklarasi ketat `strict_types=1` dan *final*).
- [ ] Indeks tabel *composite* terwujud (`category_id` + `year`).
- [ ] Pencegahan *Minus* terverifikasi (`unsigned` untuk *value* dan *year*).
- [ ] Perintah Laravel Pint sukses dieksekusi memformat kepingan M8 (*exit code 0*).
- [ ] Seeder berlaku *Idempotent* (mampu mengabaikan duplikasi jika *run* dua kali).
- [ ] Model M8 dikonfirmasi absolut **tidak** melibatkan trait *Ulid*, *Slug*, dan *Soft Deletes*.
