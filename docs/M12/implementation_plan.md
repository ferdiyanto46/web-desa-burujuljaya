# 📝 Implementation Plan — Module 12 (Website Management)

## 1. Objective
Menerjemahkan kerangka teoretis *Design Document M12* ke dalam baris kode nyata pangkalan data dan ekosistem Eloquent. Objektif puncaknya adalah membangun subsistem manajemen *Frontend* (Banners, FAQs, dan Contact Messages) yang lincah disajikan ke publik, imun terhadap serangan IDOR (via ULID), optimal dibaca secara berurutan (melalui optimasi indeks), serta bersih dari limbah fail (via penjagaan I/O Storage).

## 2. Scope
- **3 Database Migrations**: Tabel `banners`, `faqs`, dan `contact_messages`. Dilengkapi komposisi tipe data tangguh (BOOLEAN, VARCHAR, TEXT), parameter absolut *Soft Deletes* (untuk pesan warga), integrasi ULID, serta racikan kustom *Composite Index* B-Tree.
- **3 Eloquent Models**: Pembentukan `Banner.php`, `Faq.php`, dan `ContactMessage.php`. Berarsitektur statis *final class*, *strict_types=1*, terpasang `$fillable` eksplisit, tipe *Casting*, dan metode pencarian *Query Scopes* eksklusif (`scopeActive`, `scopeOrdered`, `scopeUnread`).
- **1 Observer**: Penetapan `BannerObserver.php` guna menyapu fail lampiran di *Storage* (*Storage Leak* mitigasi).
  - **Siklus Hidup**: Terdiri dari fungsi `deleted()` (menghapus gambar saat spanduk di-*hard delete*) dan `updated()` (menghapus gambar lama saat kolom `image_path` diubah).
  - **Atribut**: Diikat dengan asuransi `public bool $afterCommit = true;` agar eksekusi fisik sinkron dengan keberhasilan transaksi basis data.
- **3 Factories & 3 Seeders**: Rangkaian cetak biru relasi palsu yang dikunci ketat via instruksi `.limit(10)` demi menekan ledakan alokasi RAM perakitan data peladen (Development Safety).
- Pendaftaran akhir di poros `DatabaseSeeder.php`.

## 3. Dependency Check
- **Relasi Bebas (Zero-Coupling)**: Modul presentasi ini didesain steril seutuhnya, tidak memiliki akar keterikatan *Foreign Key* ke tabel `users` maupun entitas mana pun. Dapat dieksekusi, dihapus, maupun dimigrasikan secara sepihak dan independen.

## 4. Execution Order

**Step 1: Pembangunan Fondasi Database (Migrations)**
- Eksekusi serangkai *Artisan command* pembuatan migrasi.
- Pendefinisian kolom spesifik (menolak pemanggilan kaku) serta pemasangan blok *Composite Index* berbasis *Left-Most Prefix* (`is_active` bersama `sort_order`).

**Step 2: Pembangunan Core Eloquent & Observers**
- Penulisan spesifikasi model-model `Banner`, `Faq`, dan `ContactMessage`.
- Penanaman Trait (`HasUlid`, `SoftDeletes` eksklusif kontak).
- Penulisan perisai `BannerObserver` lalu penjalinan atribut modern `#[ObservedBy()]`.

**Step 3: Fabrikasi Data Simulasi (Factories & Seeders)**
- Merumuskan fungsi algoritma pembentuk frasa fiktif yang realistis di `BannerFactory`, `FaqFactory`, dan `ContactMessageFactory`.
- Konstruksi pemanggilan statis berbatas pada seluruh komponen Seeder.

**Step 4: Pemusatan & Pengujian Format (Registry & Linter)**
- Penanaman rantai panggil pada berkas `DatabaseSeeder.php`.
- Orkestrasi basis data terminal: `php artisan migrate:fresh --seed`. (Atau cukup melakukan *migrate* + *seed* modul khusus ini untuk mempercepat waktu pengembangan iteratif).
- Sterilisasi wujud kode via *Laravel Pint* (`vendor/bin/pint --format agent`).

**Step 5: Hold For Verification**
- Tahap ini akan langsung disambung ke implementasi sesungguhnya jika Pengguna memberikan lampu hijau kelanjutan eksekusi sistem.

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| **Storage Leakage (Fail Mengendap)** | Saat spanduk dipangkas permanen (*hard delete*) atau gambar aslinya diganti (*update image_path*), gambar versi lama tertinggal abadi dan melahap sisa memori diska peladen desa. **Mitigasi**: Pemasangan `BannerObserver` yang bereaksi secara harfiah menghapus serpihan fisik _(Storage disk)_ pada momen `deleted()` maupun `updated()`, yang dikunci keamanannya dengan `afterCommit = true`. |
| **Full Table Scan Front-End** | Warga mengakses halaman beranda dan peladen memaksa mesin menelusuri jutaan record spanduk usang secara buta. **Mitigasi**: Konstruksi `INDEX(is_active, sort_order)`. Mesin SQL akan seketika mencomot data tayang teratas murni dari memori indeks. |
| **Insecure ID Hacking (IDOR)** | Penyerang (Hacker) iseng menembak rute `/messages/2/delete` berurutan menggunakan bot otomatis. **Mitigasi**: Penyamaran identifikasi entitas (URL parametrik) mutlak dialihkan ke 26 untaian alfanumerik `ULID` acak murni. |
| **Mass-Assignment Override** | Peretas membajak *Request Array* memaksakan `['is_read' => true]` dari formulir aduan luar. **Mitigasi**: Isolasi kaku pemanggilan kolom lewat restriksi *explicit array* `$fillable` di ketiga *Model*. |

## 6. Rollback Strategy
Apabila terjadi cacat *Runtime* pada kode migrasi di saat uji muat:
1. Menyingkirkan entitas `BannerSeeder`, `FaqSeeder`, dan `ContactMessageSeeder` dari batang relasi `DatabaseSeeder.php`.
2. Menghapus kode kelas *(Model, Observer, Migration, Factory, Seeder)* di mesin lokal.
3. Menembakkan penarikan paksa kueri: `php artisan migrate:rollback --step=3`.

## 7. Acceptance Checklist
- [ ] Ketiga migrasi mendarat ke DB ditopang parameter *Composite Index* presisi.
- [ ] Atribut *Observer* terpasang patuh di model spanduk menggendong *flag* asuransi `public bool $afterCommit = true;`.
- [ ] Model spanduk mengeksekusi penghapusan diska dengan presisi melalui dua metode siklus hidup: `deleted()` dan `updated()`.
- [ ] Entitas `ContactMessage.php` sukses mengakomodasi balok proteksi *SoftDeletes*.
- [ ] Keteraturan leksikal diotentikasi langsung oleh pesan purnatugas `Exit Code 0` dari robot *Laravel Pint*.
- [ ] Pangkalan data imun dari jebakan iterasi relasional kueri N+1.
