# 📝 Implementation Plan — Module 10 (Complaints & Aspirations)

## 1. Objective
Menerjemahkan draf *Design Document* M10 yang telah direvisi ke dalam basis baris kode riil secara presisi. Tujuan eksekusi ini adalah menciptakan fondasi *Eloquent* 4-entitas *(Categories, Complaints, Attachments, Histories)* yang menaati pilar *strict_types* & *final class*, merekatkan dua buah mekanisme _Observer_ (untuk pengamanan konstrain `RESTRICT` DB dan perlindungan _Storage Leak_), serta menelurkan pangkalan data aduan warga berlapis proteksi privasi ULID dan *SoftDelete*.

## 2. Scope
- **4 Migrations**: Penulisan skema kategori, pelaporan, multi-lampiran ber-ULID, dan histori dengan ekstensi aktor penanggap (`actor_type`, serta FK `nullOnDelete`). Skema dipagari kaitan mutlak `restrictOnDelete`.
- **4 Eloquent Models**: Pembentukan `ComplaintCategory`, `Complaint`, `ComplaintAttachment`, `ComplaintHistory` dengan enkapsulasi `$fillable`, _Query Scopes_, serta *Trait* ULID & *SoftDelete*.
- **2 Observers**: 
  - `ComplaintObserver` untuk melakukan loop manual `forceDelete()` pada relasi *attachments* ketika laporan utama dimusnahkan.
  - `ComplaintAttachmentObserver` yang mengendus event mutasi memori (`updated`, `forceDeleted`). Keduanya diproteksi kelambatan transaksional `$afterCommit = true`.
- **4 Factories**: Merajut properti data palsu untuk semua keempat model entitas di atas (lengkap dengan tipe aktor).
- **2 Seeders**: `ComplaintCategorySeeder` untuk *lookup* konstan dan `ComplaintSeeder` yang menjalankan fabrikasi menggunakan iterasi `recycle()` serta isolasi tarikan data kewajaran `.limit(10)` demi mencegah _Out of Memory_ di masa _development_.
- Modifikasi pemanggil utama `DatabaseSeeder.php`.

## 3. Dependency Check
- Keberhasilan _Foreign Key_ `user_id` di dalam tabel `complaint_histories` bergantung utuh pada eksistensi **Modul 1 (Users)**. (Aturan `nullOnDelete()` menuntut sistem untuk tidak panik jika *User* dihapus).

## 4. Execution Order

**Step 1: Migrations Layer**
- Eksekusi *schema builder* untuk `complaint_categories`.
- Eksekusi *schema builder* untuk `complaints` dengan pembelahan ganda indeks komposit untuk optimasi Dasbor: `(status, deleted_at, created_at)` dan `(category_id, deleted_at, created_at)`.
- Eksekusi *schema builder* untuk `complaint_attachments` (dengan injeksi `CHAR(26) ulid`).
- Eksekusi *schema builder* untuk `complaint_histories` (menambahkan `actor_type` & nullable `user_id`).

**Step 2: Models & Observer Layer**
- Menulis keempat *Model* berpredikat `declare(strict_types=1)` dan _Final Class_.
- Menyisipkan *Trait HasUlid* dan pengikat properti pada `Complaint` dan `ComplaintAttachment`.
- Merangkai manuver pencegah _Storage Leak_ pada `ComplaintAttachmentObserver`.
- Merangkai manuver peretas konstrain `RESTRICT` pada `ComplaintObserver` lewat perulangan manual saat `forceDeleting()`.
- Menempelkan _PHP Attribute_ `#[ObservedBy()]` pada kepala kedua *Model*.

**Step 3: Fake Data Generation (Factories & Seeders)**
- Merumuskan fabrikasi data. Khusus log sejarah (*histories*), fabrikator akan meniru skenario aktor *"system"*, *"citizen"*, atau *"admin"*.
- Menyusun penanaman `ComplaintSeeder` di mana ia memanggil relasi melalui jaring memori aman `.limit(10)` pada kuota aktor Users.

**Step 4: Registry & Format Normalization**
- Meregistrasikan kepingan *Seeder* di akhir berkas `DatabaseSeeder.php`.
- Orkestrasi terminal: `php artisan migrate`, `php artisan db:seed --class=ComplaintCategorySeeder`, `php artisan db:seed --class=ComplaintSeeder`.
- Restrukturisasi leksikal: `vendor/bin/pint --format agent`.

**Step 5: Hold For Verification**
- Berhenti tanpa membuat Verification Report hingga komando berikutnya disuarakan.

## 5. Risks
| Risiko | Mitigasi |
|---|---|
| **Storage Leak Mismatch (Ghost Records)** | Terjadi pembatalan kueri (*Rollback DB*), namun fail pelaporan kadung hancur di folder publik. **Mitigasi**: Pemasangan bendera `public bool $afterCommit = true;` menunda siklus I/O seluruh kelas Observer. |
| **SQL Constraint Exception pada Cascade Delete** | Penolakan MySQL ketika *Admin* memaksa hapus Laporan secara permanen akibat pilar `RESTRICT`. **Mitigasi**: `ComplaintObserver` memicu pengosongan rekaman lampiran *secara individual* tepat pada _event_ `forceDeleting` sebelum komando ke MySQL dilayangkan. |
| **IDOR Exploitation via URL Download** | Publik menebak unduhan bukti laporan rahasia warga lain. **Mitigasi**: `ComplaintAttachment` resmi dilindungi enkripsi acak `ULID` 26 karakter untuk pengunduhan di rute publik. |
| **Out-of-Memory pada Fase *Seeding*** | Menghasilkan ratusan komplain bercabang historis yang membekukan kapabilitas RAM PC pengembangan. **Mitigasi**: Koleksi kueri User/Kategori diseleksi dengan aturan murni `.limit()` dan dirotasikan kembali lewat `.recycle()`. |

## 6. Rollback Strategy
Jika kompilasi tersendat akibat kesalahan teknis:
1. Blok dan nonaktifkan panggilan Seeder dari `DatabaseSeeder.php`.
2. Kosongkan semua artefak *Models*, *Observers*, dan *Factories* kepunyaan M10.
3. Eksekusi: `php artisan migrate:rollback --step=4`.

## 7. Acceptance Checklist
- [ ] 4 Berkas migrasi mengakar tangguh bersama pilar _Restrict_ dan `nullOnDelete()`.
- [ ] 4 Model mengabdi pada *Strict Type*, *Final*, dan anti injeksi *Mass Assignment*.
- [ ] Model lampiran kebal IDOR melalui penerapan *Trait HasUlid*.
- [ ] 2 Jenis *Composite Index* sukses dirangkai memenuhi skenario Dasbor di tabel *complaints*.
- [ ] Filter pembunuh N+1 (`scopeWithDetails`) sigap menelurkan _Eager Loading_.
- [ ] `ComplaintAttachmentObserver` (tugas sapu fail) dan `ComplaintObserver` (tugas tembus constraint *forceDelete*) berdiri tegak memegang mandat `$afterCommit`.
- [ ] `Laravel Pint` menampilkan skor murni *Exit Code 0*.
