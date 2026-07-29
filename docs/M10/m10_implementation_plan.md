# 📝 Implementation Plan — Module 10 (Complaints & Aspirations)

## 1. Objective
Merealisasikan arsitektur pengaduan *(Complaints)* warga sesuai _Design Document_ M10. Puncak implementasi ini adalah menyajikan struktur Model *Eloquent* yang tangguh (bebas anomali *Mass Assignment*), merangkai integrasi penghapus berkas bukti aduan otomatis melalui Observer yang kebal kegagalan kueri transaksional, serta memastikan pangkalan data siap menampung lonjakan masukan publik dengan dukungan algoritma *Soft Delete* mutlak dan penyamaran tiket via *ULID*.

## 2. Scope
- **1 Database Migration**: Penciptaan spesifikasi tabel tunggal padat fitur bernama `complaints`. Memuat *Foreign Key* `replied_by` dengan skenario `nullOnDelete()`.
- **1 Eloquent Model**: Pembentukan `Complaint.php` *(final class, strict_types=1)* lengkap dengan penyisipan pelindung *$fillable*, trait komplit (*SoftDeletes, HasUlid*), *casts*, dan dua set *Query Scope* kustom (`scopeUnread`, `scopeWithResponder`).
- **1 Observer**: Pembuatan kelas `ComplaintObserver` bermuatan variabel penjaga `public bool $afterCommit = true;` untuk menghapus lampiran pada dua event krusial (`forceDeleted`, `updated`).
- **1 Factory & 1 Seeder**: Menciptakan `ComplaintFactory` *(berisi simulasi data aduan bervariasi)* dan penempatan alur panggilannya melalui koleksi terbatas `.limit(10)` di dalam `ComplaintSeeder.php`.
- **Modifikasi DatabaseSeeder**: Menyambungkan pelatuk Seeder baru ke dalam ekosistem eksekusi utama.

## 3. Dependency Check
- Keberhasilan Modul 10 ini menyandarkan salah satu _Foreign Key_ miliknya (`replied_by`) kepada eksistensi **Modul 1 (Users)** yang bertugas sebagai *Responder* atau penanggap keluhan warga.

## 4. Execution Order

**Step 1: Pembangunan Database Migration**
- Men-generate dan merumuskan kolom tabel `complaints`.
- Memvalidasi pembatas *nullOnDelete* dan penyusunan indeks B-tree komposit yang sangat spesifik `(status, type, deleted_at, created_at)` demi kecepatan filter halaman depan tabel pelaporan.

**Step 2: Menenun Model & Observer**
- Menciptakan _model_ `Complaint` secara solid *(Final, Explicit Fillable)*.
- Membangun `ComplaintObserver` yang mendeteksi perubahan fail melalui mutasi `wasChanged('attachment_path')`.
- Merekatkan *PHP 8 Attribute* `#[ObservedBy(ComplaintObserver::class)]` di puncak _header_ entitas.

**Step 3: Memanufaktur Data Tiruan (Factory & Seeding)**
- Mendeklarasikan algoritma simulasi untuk membedakan antara status pengaduan yang belum terbaca dengan pengaduan yang telah rampung *(resolved)* serta dilengkapi dengan *dummy reply message*.
- Mengeksekusi penarikan sampel *User* pasif melalui fungsi efisien `->recycle()` pada kelas Seeder.

**Step 4: Standardisasi & Deployment**
- Menyisipkan inisiasi seeder di kelas `DatabaseSeeder.php`.
- Menginstruksikan peluncuran `php artisan migrate`, `php artisan db:seed --class=ComplaintSeeder`, serta `vendor/bin/pint --format agent` demi menjaga format _linter_.

**Step 5: Verifikasi Lanjutan**
- Menyusun dokumen *Verification Report* dan validasi paripurna seusai *coding phase* usai.

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| **Form Hijacking (Aparat Palsu)** | Oknum warga memaksa mengubah input *form front-end* memalsukan status atau mengisi tanggapan. **Mitigasi**: Pengerutan daftar izin pada variabel pelindung `$fillable`. Properti `status`, `replied_by`, `reply_message` dilarang disisipkan. |
| **Disk I/O Sync Mismatch (Ghost Records)** | Terjadi saat gagal menyimpan riwayat log aduan dalam blok *DB transaction*, namun berkas *attachment* lama kadung dihancurkan observer. **Mitigasi**: Aturan leksikal wajib: `public bool $afterCommit = true;` pada _Observer_. |
| **Orphan Row via User Deletion** | Jika aparat pensiun/dihapus, log aduan historis yang pernah ia tanggapi ikut sirna *(Cascade Delete)*. **Mitigasi**: Kebijakan `nullOnDelete` pada kunci FK `replied_by` yang membuat aparat hancur digantikan oleh nilai `NULL`, sementara teks aduan asli milik warga tetap kekal. |

## 6. Rollback Strategy
Andai terdapat kekeliruan struktural di tengah jalannya instalasi:
1. Menyingkirkan *Seeder* dari koleksi panggilan *DatabaseSeeder.php*.
2. Memusnahkan fail kode artefak spesifik bentukan Modul 10 *(Model, Migration, Observer, Factory)*.
3. Melepaskan instruksi mundur *Artisan*: `php artisan migrate:rollback --step=1`.

## 7. Acceptance Checklist
- [ ] Tabel tunggal `complaints` kokoh menduduki basis data bersama atribut `nullOnDelete`.
- [ ] Model `Complaint` tersusun _Strict Type_, _Final_, serta imun *Mass Assignment*.
- [ ] _Composite Index_ `(status, type, deleted_at, created_at)` tervalidasi ada.
- [ ] `ComplaintObserver` siaga mengeksekusi I/O berselaput `$afterCommit = true`.
- [ ] Fungsi pabrikasi Seeder beroperasional lancar meramu sampel keluhan acak hemat memori.
- [ ] _Linter_ `Laravel Pint` mengonfirmasi 100% kepatuhan format (Exit Code 0).
