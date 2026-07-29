# 🏗️ Service Layer Architecture Plan

> **Status**: Approved by Senior Software Architect (Refactored)

Dokumen ini memetakan tata urut eksekusi implementasi dan daftar komponen untuk membangun *Service Layer* secara keseluruhan bagi aplikasi Desa Burujuljaya. Seluruh *Service* telah dikonsolidasikan guna menanggulangi duplikasi *(DRY)*, membentengi *Clean Architecture*, dan selaras dengan `Service Layer Design Document.md`.

## 1. Daftar Keputusan Arsitektur yang Dikunci
- **Potentials**: Tidak ada entitas `PotentialService` gabungan. Logika dipisahkan murni ke `UmkmService`, `TourismService`, dan `ProductService`.
- **Orkestrasi File Cleanup**: Lapis *Service* lepas tangan dari urusan penghapusan diska. Seluruh I/O *delete* dikelola absolut oleh **Observer**.
- **Orkestrasi Audit Trail**: *Service* lepas tangan dari pemanggilan riwayat log. Hal tersebut adalah monopoli tugas **Observer** yang menembakkannya ke **ActivityLogService**.
- **Transaksi Basis Data**: Operasi kompleks yang menyenggol dua tabel atau I/O unggahan *file* wajib diikat `DB::transaction`.
- **Validasi Kaku**: Kelas bersifat `final class` berlapis `declare(strict_types=1);`.
- **Manajemen Pengecualian**: Modul *Service* menolak menelan eror (TIDAK ADA `try-catch` tersembunyi), melainkan melemparnya ke permukaan untuk dibaca oleh *Global Handler*.

## 2. Urutan Implementasi yang Diinginkan
Pembangunan *Service Layer* ini akan ditempuh melalui 5 etape fungsional yang hierarkis:

### Fase 1: Core & Foundation Services
Menyusun fondasi logika dasar pangkalan aplikasi, pengaturan *caching* statis, dan jantung *audit trail*.
- `ActivityLogService`
- `UserService`
- `VillageProfileService`
- `SettingService`

### Fase 2: Content Services
Membangun pelantang komunikasi massa desa dan logika kalender.
- `ArticleService`
- `AnnouncementService`
- `AgendaService`

### Fase 3: Media Services
Menyusun ekosistem penanganan *file* diska ukuran besar dan organisasi manusia.
- `ApparatusService`
- `GalleryService`
- `VideoService`

### Fase 4: Data & Utility Services
Membedah inti penyelesaian masalah masyarakat, pariwisata spesifik, dan administrasi angka demografi.
- `UmkmService`
- `TourismService`
- `ProductService`
- `StatisticService`
- `DocumentService`
- `ComplaintService`
- `WebsiteManagementService`

### Fase 5: Verification & Linting
Tahapan sterilisasi akhir, mencakup penyikatan spasi sintaks dengan mesin *Pint* dan observasi *strict types*.

## 3. Risiko Implementasi & Mitigasinya
| Risiko | Mitigasi |
|---|---|
| **Storage Crash / Orphan Data** | Mengunggah gambar ke Storage berhasil namun kueri pangkalan data macet *(DB crash)*. **Mitigasi**: Eksekusi metode mutlak dibungkus `DB::transaction(fn() => ...)`. Pangkalan DB akan menolak masukan bila unggahan gagal (melempar eror). |
| **Deadlock Cache** | Data *Setting* diubah Admin, tapi beranda tak berubah karena tertutup *Cache* lama. **Mitigasi**: Kewajiban pemanggilan `Cache::forget` per eksekusi fungsi perubah *(update/delete)* di tubuh *Service*. |
| **Observer Loop / Infinite Loop** | *Observer* memanggil layanan yang mengintervensi model lagi secara tiada henti. **Mitigasi**: Pemisahan peranan secara kaku; *Observer* dilarang memanggil *Service* kecuali sekadar pelemparan data satu arah ke `ActivityLogService`. |
| **Fat Service (God Object)** | `ComplaintService` merangkap peran menembak surel dan mencetak PDF. **Mitigasi**: Fokus pada *Single Responsibility Principle*. *Service* wajib sebatas orkestrasi internal, sementara ekspor/emisi didelegasikan ke entitas *Jobs* atau *Mailable*. |
