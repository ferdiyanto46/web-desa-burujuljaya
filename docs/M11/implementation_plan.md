# 📝 Implementation Plan — Module 11 (Audit & Activity Logs)

## 1. Objective
Merealisasikan cetak biru *Design Document* M11 ke dalam wujud kode yang fungsional. Puncak implementasi ini ditujukan untuk membangun ekosistem rekam jejak (*Audit Trail*) berstatus **Immutable** murni, ditopang fleksibilitas penangkapan data via *Polymorphic Relations*, serta mengorkestrasi alur pencatatan dari seluruh penjuru sistem ke satu lapis orkestrator terpusat (*Service Layer*) tanpa sedikit pun mengorbankan keamanan basis data maupun kecepatan *request* aplikasi.

## 2. Scope
- **1 Database Migration**: Pembuatan struktur tunggal polimorfik `activity_logs`. Dilengkapi formasi tipe logikal tangguh: `batch_uuid`, `event`, peniadaan mutlak konstrain *Foreign Key*, ketiadaan absolut fungsi *updated_at*, serta racikan 4 unit *Composite Index* B-Tree.
- **1 Eloquent Model**: Kelas final dan ketat `ActivityLog.php` dengan deklarasi penguncian `$fillable`, ketiadaan *SoftDeletes*, serta peleburan *Trait ULID* dan kapabilitas *JSON Cast* untuk parameter matriks `properties`.
- **1 Service Class**: Konstruksi dasar fondasi `ActivityLogService` sebagai pintu gerbang (*orchestrator*) tunggal yang akan mengonstruksi entitas baris log yang didatangkan oleh Modul internal lainnya kelak.
- **1 Factory & 1 Seeder**: Penciptaan fabrikasi data (lengkap dengan template struktur `old`, `attributes`, dan `extra`), diikuti isolasi kuota iterasi Seeder murni berbatas `.limit(15)` data guna kepentingan demonstrasi visual dasbor belaka.
- Injeksi inisiasi pada poros induk `DatabaseSeeder.php`.

## 3. Dependency Check
- **Zero-Dependency Tolerance**: Karena struktur berpedoman pada hukum polimorfisme sejati, Modul 11 didesain **merdeka absolut** dari konstrain entitas Modul 1-10. Tabel *log* dijamin tidak akan merusak struktur basis data jikalau tabel lain dikosongkan.

## 4. Execution Order

**Step 1: Pembangunan Database Migration**
- Men-*generate* arsitektur migrasi kustom untuk pangkalan `activity_logs`.
- Membuang pemanggilan standar `timestamps()` bawaan dan menggantinya eksklusif dengan kolom absolut `created_at`.
- Mengimplementasikan barikade lapis ke-4 *Composite Indexes* untuk mengantisipasi jutaan *row* di masa depan.

**Step 2: Pembangunan Core Eloquent & Service Layer**
- Menulis kode murni `ActivityLog` dengan pelabelan `declare(strict_types=1)` dan *final class*.
- Merumuskan fungsi kueri agregasi *Query Scope* (`scopeInLog`, `scopeCausedBy`, `scopeForSubject`).
- Menciptakan cikal bakal file sentral `app/Services/ActivityLogService.php` guna menyusun metodologi *batching* (`batch_uuid`) serta konversi parameter *Casting JSON*.

**Step 3: Pembangunan Pabrikasi Data (Factories & Seeders)**
- Mendeklarasikan arsitektur fabrikasi simulasi `ActivityLogFactory` dengan parameter label (`event`) konstan.
- Merakit `ActivityLogSeeder` serta menguncinya di balik metode perulangan `.limit(15)`.

**Step 4: Registry & Format Normalization**
- Menambahkan kelas Seeder pada deretan akhir panggilan `DatabaseSeeder.php`.
- Orkestrasi artisan via terminal: `php artisan migrate`, `php artisan db:seed --class=ActivityLogSeeder`.
- Restrukturisasi leksikal menggunakan standarisasi PSR murni: `vendor/bin/pint --format agent`.

**Step 5: Hold For Verification**
- Proses berhenti, tidak akan menghasilkan *Verification Report* atau *coding* ekstra hingga diinstruksikan persetujuan (*Approval*) dari Arsitek Sistem (Pengguna).

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| **Form/Request Log Hijacking** | Penyerang (Hacker) mendiktekan parameter log usil dari tangkapan form formulir liar. **Mitigasi**: Larangan *Mass-Assignment* (*No Fillable*) pada kelas log. Pembentukan kolom hanya dilakukan oleh kelas internal yang terjaga murni: `ActivityLogService`. |
| **Cascade Data Loss (Orphan Crash)** | Aparat memusnahkan artikel atau petugas pengunggah (*Causer*) aslinya secara absolut di basis data. **Mitigasi**: Implementasi *Zero Foreign Key Constraint* pada tabel log. Riwayat tetap utuh (mengembalikan pola *Null Object Pattern* saat dibaca *Dashboard*). |
| **XSS Injection via Properties** | Peretas mengubah nama judul Desa menjadi `<script>` dan membakar kolom dasbor Admin lewat bacaan Log. **Mitigasi**: Parameter pelacakan dienkapsulasi kuat pada balok *Array/JSON Cast* Eloquent *(Laravel Serialization Protection)*. |
| **Massive I/O Database Exhaustion** | Ledakan penyimpanan ketika aplikasi memproduksi jutaan log (A08). **Mitigasi**: Kepatuhan pembentukan *Composite Index* (*Left-Most Prefix*) mencegah macetnya SQL saat Admin menilik dasbor penyaringan kronologi lampau. |

## 6. Rollback Strategy
Jika terdapat cacat dalam skema migrasi logikal pada saat pembangunan:
1. Memotong pemanggilan pelatuk `ActivityLogSeeder` dari tubuh utama `DatabaseSeeder.php`.
2. Melenyapkan berkas kelas kustom M11 (Migrasi, Model, *Service*, maupun *Factory*).
3. Mengeksekusi penarikan kueri mundur *Artisan*: `php artisan migrate:rollback --step=1`.

## 7. Acceptance Checklist
- [ ] Berkas migrasi mengakar tangguh ke DB tanpa kolom cacat bawaan (`updated_at` / `deleted_at`).
- [ ] *Composite Index* 4 keping berjejer melayani optimalisasi *Full Table Scan* kueri Dasbor.
- [ ] Parameter polimorfik murni beroperasi di *Database* tanpa menjerat rujukan *Foreign Key*.
- [ ] Ekosistem JSON berlapis *(old, attributes, extra)* diikat presisi di Model Eloquent via *Casting*.
- [ ] Pilar *Immutable Class* (tiada penghapusan/pembaruan data log) dibentengi mutlak oleh `ActivityLog.php`.
- [ ] Kelas konduktor sentral `ActivityLogService` tervalidasi menempati direktori `app/Services/`.
- [ ] *Agent Linter Laravel Pint* menampilkan pesan purnatugas *Exit Code 0*.
