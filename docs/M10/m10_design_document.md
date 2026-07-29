# 📑 Design Document — Module 10 (Complaints & Aspirations)

## 1. Tujuan Modul
Modul **Complaints & Aspirations (Pengaduan & Aspirasi)** adalah jembatan komunikasi digital yang memungkinkan masyarakat desa mengirimkan laporan, masukan, maupun aduan langsung kepada Pemerintah Desa. Modul ini dirancang dengan tingkat keamanan tinggi menggunakan `ULID` untuk mencegah kebocoran identitas pelapor dari peretasan ID berurutan, dilindungi algoritma *Soft Delete*, serta dipertajam dengan sistem pelacakan lampiran fisik (*attachment*) yang memicu *Observer* demi menjaga memori pangkalan data tetap higienis.

## 2. Struktur Database

### Tabel: `complaints` (Tabel Mandiri Tunggal)
Mengakomodasi data pengirim, isi aduan, bukti foto/lampiran, dan umpan balik (*reply*) dari aparat desa.

| Kolom | Tipe Data | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Primary Key, Auto Increment |
| `ulid` | CHAR(26) | NO | Identifier eksternal Publik (Anti IDOR), `UNIQUE` |
| `name` | VARCHAR(255) | NO | Nama pelapor/pengirim |
| `email` | VARCHAR(255) | YES | Kontak surel opsional |
| `phone` | VARCHAR(20) | YES | Kontak telepon/WhatsApp opsional |
| `type` | VARCHAR(50) | NO | Enum tiruan: `pengaduan`, `aspirasi`, `laporan` |
| `title` | VARCHAR(255) | NO | Judul aduan |
| `body` | TEXT | NO | Deskripsi lengkap kronologi/pesan |
| `attachment_path` | VARCHAR(500) | YES | Lokasi lampiran berkas/bukti foto |
| `status` | VARCHAR(50) | NO | Enum tiruan: `unread`, `processing`, `resolved`, `rejected` |
| `replied_by` | BIGINT UNSIGNED | YES | FK ke tabel `users` (Aparat yang menanggapi) |
| `reply_message` | TEXT | YES | Pesan balasan/tanggapan dari desa |
| `created_at` | TIMESTAMP | YES | Laravel Timestamps |
| `updated_at` | TIMESTAMP | YES | Laravel Timestamps |
| `deleted_at` | TIMESTAMP | YES | Sinyal Soft Delete |

## 3. Alasan Pemilihan Tipe Data
- `CHAR(26)` pada `ulid`: Esensial untuk melindungi privasi pengadu. Publik atau pengguna jahat tidak bisa meraba ID pelapor secara iteratif *(Mass ID Enumeration)*.
- `VARCHAR(50)` pada `status` & `type`: Ketimbang menggunakan enum primitif MySQL, Laravel lebih disarankan memakai kolom VARCHAR standar berbatas 50 karakter yang divalidasi lewat kelas Enum PHP (Backed Enum).
- `VARCHAR(20)` pada `phone`: Lebih dari cukup untuk menampung format kode negara seperti `+6281234567890`.
- `VARCHAR(500)` pada `attachment_path`: Kapasitas string mumpuni untuk direktori bertingkat *Storage Local* maupun abstraksi *Cloud S3*.

## 4. Foreign Key Rules
- `complaints.replied_by` -> `users.id` (Mewakili aparat/admin yang mengeksekusi penyelesaian aduan). 
- Menggunakan klausul `NULLABLE`, karena aduan masuk (berstatus `unread`) tidak langsung memiliki penanggap.

## 5. Cascade Rules
- **`nullOnDelete()`**: Berbeda dengan aturan keras tabel konten murni, pengaduan tidak serta merta gugur jika aparat *(User)* yang pernah menanggapinya dipecat/dihapus permanen. Data histori pengaduan warga tetap harus abadi, sehingga relasi FK `replied_by` wajib digeser menjadi `NULL` jika *User* tersebut punah.

## 6. Index Strategy
- **Composite Index**: `(status, type, deleted_at, created_at)`.
  *Alasan:* Pada *Dashboard Admin*, aparat lazimnya menyaring data dengan parameter spesifik seperti *"Tampilkan Pengaduan (type) yang belum dibaca (status) dari yang terlama/terbaru (created_at)"*. Komposit B-Tree ini bekerja sempurna (*Covering Index-like*) melayani kebutuhan matriks dasbor tersebut.
- **Single Index**: `ulid` (Untuk pelacakan nomor *tracking* pengaduan milik warga melalui beranda depan).

## 7. Model Design
- Menggunakan `final class Complaint extends Model`.
- Ditutup rapat dengan `declare(strict_types=1)`.
- Mengimplementasikan Array `$fillable` yang eksplisit: Parameter penanggap seperti `status`, `replied_by`, dan `reply_message` berstatus terlarang (*restricted*) dan tidak boleh masuk `$fillable` agar pengguna usil dari luar (*Front-End*) tidak mampu memanipulasi form hingga memanipulasi penyelesaian sepihak.
- Tergabung dengan Traits `HasUlid` dan `SoftDeletes`. (Trait `HasUniqueSlug` **tidak** diimplementasikan karena aduan publik tidak berwujud artikel ber-SEO).

## 8. Query Scopes
- `scopeUnread(Builder $query)`: Memfilter data mendesak dengan `status = unread`.
- `scopeByType(Builder $query, string $type)`: Menyaring pengaduan, atau laporan.
- `scopeWithResponder(Builder $query)`: Menginjeksi *Eager Loading* `['responder']` untuk relasi (alias dari `replied_by`) sebagai garda *Anti N+1 Query*.

## 9. Observer
- **`ComplaintObserver`** sangat diwajibkan (Serupa mekanika Modul 9). 
- **Tugas**: Mencabut lampiran foto/dokumen pengaduan di diska fisik (`Storage::delete`) ketika aduan dimusnahkan selamanya melalui `forceDeleted()`, atau ketika lampiran dipelintir melalui `updated()`.
- Wajib memiliki proteksi kelambatan transaksi `public bool $afterCommit = true;` demi mengawal kegagalan DB Rollback.
- Dilekatkan dengan atribut mutakhir PHP 8: `#[ObservedBy(ComplaintObserver::class)]`.

## 10. Factory Strategy
- **`ComplaintFactory`**: Mensimulasikan nama warga, judul keluhan jalan rusak atau sampah, isi (*paragraph*), *fake attachment path*, dengan komposisi *status* bervariasi (70% unread/processing tanpa responder, 30% resolved memiliki _responder User ID_ dan _reply message_).

## 11. Seeder Strategy
- Mengaitkan diri secara pasif ke tabel `User`.
- `User::inRandomOrder()->limit(10)->get()` akan dimuat untuk menjadi aktor penanggap (_responder_).
- Melakukan rotasi `.recycle()` terhadap sampel 50 aduan acak agar pangkalan data tidak ambruk (_OOM Prevention_).

## 12. Security Review (OWASP Top 10)
- **A01: Broken Access Control**: Pemakaian ULID mencegah kebocoran data pelapor lain. Hanya pelapor yang memegang nomor tiket (ULID) miliknya yang bisa memantau progres keluhannya.
- **A03: Injection**: Semua masukan formulir di-*cast* dan dibatasi melalui struktur Model Eloquent dan pembatasan Tipe Data *strict*.
- **A08: Software and Data Integrity Failures**: Properti krusial seperti `status` dan tanggapan `reply_message` dicoret permanen dari array `$fillable`. Pembaruan status di *Service Layer* kelak wajib menginjeksi perintah manual `$model->status = 'resolved'`, mencekik serangan eksploitasi form HTTP massal (*Mass-Assignment Vulnerability*).

## 13. Performance Review
- Modul kebal dari beban `N+1` melalui peresmian metode `scopeWithResponder`.
- *Composite Index* beranggotakan 4 lajur menjamin navigasi data pengaduan di meja aparat *Front Desk* tetap gegas hingga ratusan ribu laci arsip *row*.

## 14. Laravel Best Practice
- Observasi dilegalisasi elegan via atribut model `#[ObservedBy()]`.
- Metode relasi Eloquent merujuk `BelongsTo` dari *foreign_key* kustom (`replied_by`).
- Format leksikal mutlak dipercaya kepada **Laravel Pint** (*agent rule*).

## 15. Daftar File yang Akan Dibuat
1. `database/migrations/2026_07_24_100000_create_complaints_table.php`
2. `app/Models/Complaint.php`
3. `app/Observers/ComplaintObserver.php`
4. `database/factories/ComplaintFactory.php`
5. `database/seeders/ComplaintSeeder.php`
- Serta melakukan *editing* registrasi pada `DatabaseSeeder.php`.
