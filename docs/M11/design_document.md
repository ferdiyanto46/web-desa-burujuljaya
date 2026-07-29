# 📑 Design Document — Module 11 (Audit & Activity Logs)

> **Status**: Draft by Senior Software Architect

## 1. Tujuan Modul
Modul **Audit & Activity Logs** (Module 11) adalah subsistem pelacakan rekam jejak (*Audit Trail*) tersentralisasi untuk Website Desa Burujuljaya. Bertujuan untuk mencatat setiap aktivitas fundamental (seperti pembuatan, pengubahan, atau penghapusan data) oleh aparat desa maupun sistem. Modul ini beroperasi selayaknya "Black Box" yang merajut integritas data tingkat tinggi melalui sifat pelaporan yang **Immutable** (tak dapat diubah/dihapus) dan menggunakan relasi polimorfik untuk mencakup segala tipe entitas yang ada di dalam aplikasi.

---

## 2. Struktur Tabel Lengkap

### Tabel: `activity_logs`
Tabel tunggal pelaporan yang dirancang murni **Immutable**. Tabel ini kebal dari mutasi data (*Update*) maupun penghapusan (*Soft/Force Delete*).

| Kolom | Tipe Data | Nullable | Default | Keterangan / Alasan Pemilihan Tipe Data |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Auto Inc | Primary Key standar tabel relasional. |
| `ulid` | CHAR(26) | NO | None | Unique ID publik mencegah *IDOR/Enumeration*. Menjamin ukuran statis 26 karakter. |
| `batch_uuid` | CHAR(36) | YES | NULL | Pengelompokan log dari satu transaksi (e.g. Delete User -> Delete Roles). |
| `log_name` | VARCHAR(255) | NO | 'default' | Pengelompokan makro log (`system`, `security`, `content`). |
| `event` | VARCHAR(100) | NO | None | Jenis kejadian konstan (`created`, `updated`, `deleted`, `login`). Cepat untuk filter. |
| `description` | TEXT | NO | None | Deskripsi manusiawi (human-readable) untuk dibaca Admin di dasbor. |
| `subject_type` | VARCHAR(255) | YES | NULL | Tipe Model target polimorfik (contoh: `App\Models\Article`). |
| `subject_id` | BIGINT UNSIGNED | YES | NULL | ID numerik dari Model target polimorfik. |
| `causer_type` | VARCHAR(255) | YES | NULL | Tipe Model Aktor polimorfik (contoh: `App\Models\User`). |
| `causer_id` | BIGINT UNSIGNED | YES | NULL | ID numerik aktor pelaksana tindakan. |
| `properties` | JSON | YES | NULL | Menyimpan perubahan delta terstruktur (`old`, `attributes`, `extra`). |
| `ip_address` | VARCHAR(45) | YES | NULL | Alamat IPv4/IPv6. Nullable karena aksi Queue/Seeder tak ber-IP. |
| `user_agent` | TEXT | YES | NULL | Browser/OS aparat. Tipe TEXT mengamankan string agen mutakhir yang panjang. |
| `created_at` | TIMESTAMP | YES | CURRENT | Waktu absolut pelaporan. Tidak ada kolom `updated_at`. |

---

## 3. Foreign Key Rules
- **Tidak ada (Zero Foreign Key Constraints)**.
- *Alasan*: Tabel ini merajut relasi polimorfik. Secara arsitektural basis data, entitas *morph* (`subject_id`, `causer_id`) dilarang diikat oleh *Foreign Key* kaku ke satu tabel. Keutuhan data dipertahankan secara artifisial melalui aplikasi Eloquent.

## 4. Cascade Rules
- **Tidak ada aturan Cascade (*Orphan Retention*)**.
- *Alasan*: Seluruh log audit bersifat abadi. Apabila kelak aktor (contoh: Admin) atau subjek dokumen (contoh: Artikel) dimusnahkan secara permanen, baris rekam jejak kerja di tabel `activity_logs` tidak akan dan tidak boleh ikut gugur. Relasi Eloquent yang putus akan mengembalikan *Null Object Pattern*.

## 5. Index Strategy
- **Composite Index 1**: `(log_name, created_at)`
  *Tujuan*: Menyaring seluruh log kategori 'security' terbaru.
- **Composite Index 2**: `(event, created_at)`
  *Tujuan*: Menyaring seluruh log berdasarkan peristiwa 'deleted' terbaru.
- **Composite Index 3**: `(causer_type, causer_id, created_at)`
  *Tujuan*: Melacak penuh kronologi aksi dari satu Aktor/Admin spesifik.
- **Composite Index 4**: `(subject_type, subject_id, created_at)`
  *Tujuan*: Menginspeksi riwayat masa lalu (Audit) dari satu objek spesifik.
- **Single Index 1**: `ulid` (opsional proteksi publik/URL).
- **Single Index 2**: `batch_uuid` (pengumpulan riwayat satu transaksi dengan lekas).

## 6. Model Design
- **Class**: `final class ActivityLog extends Model`.
- **Kepatuhan Tipe**: `declare(strict_types=1)`.
- **Fitur Spesial**: 
  - Tidak memiliki fungsi `$fillable` konvensional. Penulisan log hanya bisa dipicu oleh lapisan *Service*.
  - Tanpa *SoftDeletes* (log tidak boleh dihapus).
- **Casting (`casts`)**: 
  - `properties` di-_cast_ menjadi tipe logikal murni _Array/AsJson_.
- **Ulid**: Integrasi murni trait `HasUlid`.
- **Relasi Polimorfik**: Implementasi absolut metode `$this->morphTo()` untuk `causer()` dan `subject()`.

## 7. Query Scopes
- `scopeInLog(Builder $query, string|array $logNames)`: Mengerucutkan daftar pencarian pada nama log tertentu.
- `scopeCausedBy(Builder $query, Model $causer)`: Memfilter tindakan yang merujuk pada satu profil Aktor/Petugas.
- `scopeForSubject(Builder $query, Model $subject)`: Menarik seluruh goresan rekam jejak atas satu pangkalan data/baris (*row*) subjek.

## 8. Observer Design (Activity Logging Strategy)
- **Model `ActivityLog` TIDAK memiliki Observer murni**. 
- **Arsitektur Pemusatan (Service Pattern)**: Observer justru berasal dari modul-modul lain (misal: Modul Artikel, Pengaduan, Pengguna). Seluruh *Observer* eksternal tersebut akan memanggil satu entitas kelas pengatur terpusat bernama **`ActivityLogService`** (atau `ActivityLogger`).
- *ActivityLogService* bertugas mengemas masukan *Request*, membungkus *batch_uuid*, membentuk struktur `properties` ("old" & "attributes"), lalu menyimpan datanya ke entitas log. Tujuannya membunuh redundansi koding di seluruh lapis aplikasi (*DRY Principle*).

## 9. Factory Strategy
- **`ActivityLogFactory`**: Mengandalkan algoritma generasi struktur `properties` (old/attributes) valid, dan memalsukan nilai statis acak untuk label konstan `event` (`created`, `updated`, `deleted`).
- Terbatas pada produksi polimorfik semu (labeling murni).

## 10. Seeder Strategy
- **`ActivityLogSeeder`**: Dibatasi mutlak pemakaiannya murni untuk pendemonstrasian tampilan dasbor. Harus dibentengi metode iterasi `.limit(15)` data palsu saja. Penciptaan sesungguhnya akan bergulir secara organik dari modul lain di *production*.

## 11. Security Review
- **IDOR Protection**: Identifikasi luar ditopang oleh `ulid` (CHAR 26).
- **Integrity Constraints (A08)**: Parameter `batch_uuid` dicetak secara internal oleh logika peladen (*Service*), diharamkan dipanggil via kolom formulir bebas milik penyerang. Tabel dirancang absolut *Immutable* karena tak dilengkapi fasilitas migrasi kolom pembaruan.
- **XSS Protection (A03)**: Delta log diletakkan pada instrumen `properties` yang diolah mesin pemroses *JSON Casting* Eloquent guna sterilisasi *payload* HTML/Script berbahaya.

## 12. Performance Review
- **Queue Asynchronous Handling**: Sentralisasi pada **`ActivityLogService`** memberikan ruang agar fungsionalitas perekaman log kelak bisa disalurkan menuju sistem _Queue Worker_. Dengan cara ini, beban komputasi masif I/O pencatatan (saat *traffic* tinggi) tidak akan menyiksa dan mencekik respons waktu Request HTTP pengguna utama.
- **Index Merging**: Dukungan ragam B-Tree Composite Index memastikan kueri Admin Dasbor tidak terjatuh ke perangkap *Full Table Scan*.

## 13. Laravel Best Practice
- Mengusung pilar **Laravel 13** dan **PHP 8.3**.
- Desain kode dijamin oleh kebersihan sintaks murni **Pint (Agent)**.
- Desain Polimorfik (*MorphTo*) mengikuti regulasi standar Eloquent ekosistem *Enterprise*.

## 14. Daftar Seluruh File yang Akan Dibuat
1. `database/migrations/2026_07_27_100000_create_activity_logs_table.php`
2. `app/Models/ActivityLog.php`
3. `app/Services/ActivityLogService.php` *(Service Layer, sentral orkestrasi log)*
4. `database/factories/ActivityLogFactory.php`
5. `database/seeders/ActivityLogSeeder.php`
- Penambahan kelas di `database/seeders/DatabaseSeeder.php`.
