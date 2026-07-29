# 📑 Design Document — Module 12 (Website Management)

> **Status**: Draft by Senior Software Architect

## 1. Tujuan Modul
Modul **Website Management** (Module 12) bertujuan untuk mengelola konten presentasi garis depan (*Frontend*) yang dinamis pada Website Desa Burujuljaya. Modul ini berfokus pada tiga komponen esensial:
1. **Banners**: Pengaturan spanduk bergambar/promosi yang tampil pada beranda.
2. **FAQs (Frequently Asked Questions)**: Pusat bantuan interaktif (*accordion*) berisi tanya jawab umum untuk menekan repetisi pertanyaan warga.
3. **Contact Messages**: Wadah penampungan pesan, kritik terbuka, dan tawaran kerja sama yang dikirim oleh publik (non-pengaduan formal) ke pihak aparatur desa.

---

## 2. Struktur Tabel Lengkap

### Tabel: `banners`
Menyimpan informasi spanduk *hero* maupun promosi.

| Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Auto Inc | Primary Key standar |
| `ulid` | CHAR(26) | NO | None | Identifikasi unik untuk Route Key |
| `title` | VARCHAR(255) | NO | None | Judul spanduk untuk *alt text* / aksesibilitas |
| `image_path` | VARCHAR(255) | NO | None | Lokasi file gambar di dalam *Storage* |
| `link_url` | VARCHAR(255) | YES | NULL | Tautan aksi bila spanduk ditekan *(Call to Action)* |
| `is_active` | BOOLEAN | NO | true | Visibilitas spanduk (`1` = tayang, `0` = sembunyikan) |
| `sort_order` | INT | NO | 0 | Urutan prioritas penayangan spanduk |
| `created_at` | TIMESTAMP | YES | NULL | Waktu pembuatan |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu pembaruan |

### Tabel: `faqs`
Menyimpan bank pengetahuan pertanyaan umum.

| Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Auto Inc | Primary Key standar |
| `ulid` | CHAR(26) | NO | None | Identifikasi unik |
| `question` | VARCHAR(255) | NO | None | Bunyi pertanyaan |
| `answer` | TEXT | NO | None | Penjelasan / Jawaban |
| `is_active` | BOOLEAN | NO | true | Visibilitas FAQ |
| `sort_order` | INT | NO | 0 | Prioritas urutan kemunculan di layar |
| `created_at` | TIMESTAMP | YES | NULL | Waktu pembuatan |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu pembaruan |

### Tabel: `contact_messages`
Menyimpan data kontak murni dari form hubungi kami.

| Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | Auto Inc | Primary Key standar |
| `ulid` | CHAR(26) | NO | None | Anti IDOR *(Insecure Direct Object Reference)* |
| `name` | VARCHAR(255) | NO | None | Nama lengkap pengirim |
| `email` | VARCHAR(255) | NO | None | Alamat surel pengirim untuk balasan |
| `phone` | VARCHAR(20) | YES | NULL | Nomor kontak opsional |
| `subject` | VARCHAR(255) | NO | None | Perihal pesan |
| `message` | TEXT | NO | None | Isi pesan penuh |
| `is_read` | BOOLEAN | NO | false | Penanda pesan telah dibaca oleh Admin |
| `created_at` | TIMESTAMP | YES | NULL | Waktu pengiriman |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu pembaruan status |
| `deleted_at` | TIMESTAMP | YES | NULL | Kolom *Soft Delete* untuk mencegah data terhapus permanen tak sengaja. |

---

## 3. Foreign Key Rules
- Modul ini **TIDAK MEMILIKI** ketergantungan relasional (*Foreign Key*) dengan entitas modul lain (sangat independen). Pesan kontak direkam secara *hard-coded* dari input publik anonim tanpa mewajibkan relasi ke tabel `users`.

## 4. Cascade Rules
- Mengingat ketiadaan *Foreign Key*, arsitektur ini secara otomatis terbebas dari ancaman *Cascade Deletion* tak terkendali.

## 5. Index Strategy
- `banners`: `(is_active, sort_order)` — Mengoptimalkan kueri tayangan Beranda yang mengutamakan urutan spesifik.
- `faqs`: `(is_active, sort_order)` — Mengakselerasi penyajian halaman bantuan publik.
- `contact_messages`: `(is_read, created_at)` — Memudahkan Admin menyaring tumpukan *"Pesan Belum Dibaca"* secara kronologis terbalik.
- **Single Index**: Kolom `ulid` di ketiga tabel di-indeks eksklusif sebagai `UNIQUE`.

## 6. Model Design
- Tiga Model murni: `Banner`, `Faq`, `ContactMessage`.
- Dideklarasikan absolut sebagai `final class` beserta barikade `declare(strict_types=1)`.
- Menggunakan parameter `protected $fillable = [...]` eksplisit untuk menahan mutasi injeksi basis data massal.
- Penerapan Trait `HasUlid`.
- Tipe data angka (seperti `sort_order`) dan flag statis (seperti `is_active`, `is_read`) diolah menggunakan `casts()`.

## 7. Query Scopes
- `scopeActive(Builder $query)`: Mengerucutkan penarikan data yang tayang *(is_active = true)*.
- `scopeOrdered(Builder $query)`: Mengurutkan hasil otomatis berdasarkan hierarki kolom `sort_order` secara _ascending_.
- `scopeUnread(Builder $query)`: Eksklusif untuk `ContactMessage` (hanya menarik pesan yang belum direspons).

## 8. Observer Design (Jika Diperlukan)
- **`BannerObserver`**: Mengawal absolut tabel `banners`.
  - **Atribut**: Memiliki konfigurasi `public bool $afterCommit = true;` agar penghapusan fisik hanya terjadi jika transaksi basis data sukses.
  - **Siklus Hidup `deleted()`**: Ketika entitas spanduk dihapus secara permanen (*hard delete*), *Observer* akan mengeksekusi penghapusan fail `image_path` lama dari diska fisik *(Storage disk)*.
  - **Siklus Hidup `updated()`**: Ketika entitas spanduk mengalami pergantian gambar (atribut `image_path` berubah), *Observer* akan mengeksekusi penghapusan fail gambar spanduk versi sebelumnya (lama) dari *Storage disk*.
  - **Tujuan Akhir**: Mitigasi penuh terhadap risiko *Storage Leak* (limbah fail tak terpakai pada peladen).

## 9. Factory Strategy
- `BannerFactory`: Memalsukan rute acak menuju gambar di direktori publik, membubuhkan teks judul korporat palsu.
- `FaqFactory`: Menurunkan paragraf tanya jawab dari mesin cetak fiktif, mengaktifkan proporsi *true/false* secara tak seragam.
- `ContactMessageFactory`: Mensimulasikan formasi surel asli (via fungsi `safeEmail`) serta memfabrikasi narasi opini warga *(paragraph text)*.

## 10. Seeder Strategy
- Seluruh Seeder (`BannerSeeder`, `FaqSeeder`, `ContactMessageSeeder`) diarsiteki dengan batas rasional `.limit()` pencetakan (maks. 10 baris per tabel).
- Dihidupkan perannya melalui urutan final pada `DatabaseSeeder.php`.

## 11. Security Review
- **IDOR Safeguard (A01)**: Interaksi publik terhadap pesan kontak (khususnya fitur balas atau hapus) dijaga ketat oleh kunci mutlak *ULID*, menutup pergerakan bot enumerasi *(contoh: mencegah serangan brute force pada URL /messages/delete/1)*.
- **Storage Sweeping**: Sinkronisasi pembasmian gambar terbuang pada modul Spanduk (*Banner*) memastikan peladen desa tidak mati karena piringan ruang habis tergrogoti peninggalan *file* usang.
- **Mass Assignment**: Variabel yang wajib difilter otomatis seperti id diblok oleh spesifikasi `$fillable`.

## 12. Performance Review
- **B-Tree Query Targeting**: Penyaringan pengunjung *(Front-End)* terhadap FAQ (yang lazimnya dibuka setiap detik) tidak akan merayapi seluruh tabel karena telah terproteksi presisi menggunakan gabungan *Composite Index*.
- **Anti N+1**: Secara bawaan, ketiga tabel bersifat tunggal *(flat tables)* dan terlepas dari penyakit perulangan relasi ganda *(N+1 Query Issue)*.

## 13. Laravel Best Practice
- Desain arsitektur dijamin kepatuhannya terhadap parameter spesifik pangkalan data **Laravel 13** dan leksikal **PHP 8.3**.
- Penyandingan perulangan operasi diska di dalam `afterCommit = true` menjaga keutuhan file apabila transaksi basis data seketika gagal.
- Penyatuan gaya visual sintaks dengan penyikatan otomatis *Pint Agent*.

## 14. Daftar Seluruh File yang Nanti Akan Dibuat
1. **Migrations**:
   - `create_banners_table.php`
   - `create_faqs_table.php`
   - `create_contact_messages_table.php`
2. **Models**:
   - `app/Models/Banner.php`
   - `app/Models/Faq.php`
   - `app/Models/ContactMessage.php`
3. **Observer**:
   - `app/Observers/BannerObserver.php`
4. **Factories**:
   - `database/factories/BannerFactory.php`
   - `database/factories/FaqFactory.php`
   - `database/factories/ContactMessageFactory.php`
5. **Seeders**:
   - `database/seeders/BannerSeeder.php`
   - `database/seeders/FaqSeeder.php`
   - `database/seeders/ContactMessageSeeder.php`
6. Penambahan pemanggilan ke dalam `DatabaseSeeder.php`.
