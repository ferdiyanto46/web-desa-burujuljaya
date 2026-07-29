# 📑 Service Layer Design Document

> **Status**: Approved by Senior Software Architect (Refactored)

## 1. Tujuan Service Layer
Fase ini bertujuan untuk menstrukturisasi pilar logika bisnis utama Website Desa Burujuljaya ke dalam lapis *Service Layer* murni. Lapis ini dibentuk untuk mensterilkan Controller (hanya memvalidasi HTTP Request) dan Model (hanya merepresentasikan data). Semua logika bisnis, eksekusi transaksi basis data, serta orkestrasi pemanggilan *ActivityLogService* akan dipusatkan pada *Service Layer*, menciptakan ekosistem kode yang *Reusable*, *Testable*, dan tunduk pada prinsip *Clean Architecture*.

## 2. Relasi Arsitektur yang Dikunci
- **Controller**: Murni untuk memvalidasi *Request* masuk (via Form Request), memanggil kelas *Service*, dan memulangkan *Response*.
- **Model**: Murni representasi entitas data Eloquent (tanpa injeksi logika bisnis rumit).
- **Observer**: Murni menangani *lifecycle event*, penyingkiran/pembersihan file I/O fisik (*Storage cleanup*), dan bertugas memanggil `ActivityLogService` guna pelaporan aktivitas.
- **Service**: Eksekutor transaksi basis data, pengelola memori *Cache*, dan menangani orkestrasi bisnis inti.
- **Activity Logging**: Terpusat eksklusif di dalam `ActivityLogService`.
- **Error Handling**: Setiap pengecualian (*Exceptions*) dibiarkan naik ke permukaan (*bubble up*) untuk dikurasi secara terpusat oleh *Global Exception Handler* atau *Controller*.
- **Database Transactions**: Transaksi berskala besar dan bersifat multi-tabel wajib dibungkus dalam blok perlindungan `DB::transaction()`.
- **Cache Invalidation**: Data statis wajib mempertimbangkan pembatalan *cache* (*Cache::forget*) saat entitas mengalami mutasi.

---

## 3. Desain Fundamental per Kelas Service

*Note: Setiap Service memikul standar keamanan enkapsulasi data dan pelindungan OWASP, strategi skalabilitas asinkron (kompatibel Queue), dan error handling yang terpusat ke handler global.*

### A. Core & Foundation Services

#### 1. `ActivityLogService`
- **Tujuan**: Penampung sentral rekam jejak (*Audit Trail*).
- **Tanggung Jawab Utama**: Menulis kronologi log audit yang dilempar dari *Observer*.
- **Method Inti**: `log(event, description, subject, causer, properties)`
- **Dependensi**: Model `ActivityLog`. Dipanggil oleh *Observer* eksternal.
- **Strategi File Cleanup**: *Not Applicable* (N/A).
- **Performa & Caching**: Tanpa *cache*, kompatibel didorong ke *Queue Worker* jika beban berlebih.

#### 2. `UserService`
- **Tujuan**: Manajemen kepegawaian dan otentikasi.
- **Tanggung Jawab Utama**: Mengatur pembuatan admin, penguncian akun, dan modifikasi profil aparat.
- **Method Inti**: `register()`, `ban()`, `updateProfile()`, `assignRole()`.
- **Dependensi**: Model `User`.
- **Strategi Transaksi DB**: Digunakan saat pendaftaran dibarengi peran (*Role*).
- **Strategi File Cleanup**: (N/A).
- **Keamanan**: Standar *hashing* mutlak.

#### 3. `VillageProfileService`
- **Tujuan**: Manajemen data tunggal identitas instansi.
- **Tanggung Jawab Utama**: Mengubah visi-misi dan sejarah.
- **Method Inti**: `updateProfile()`, `getProfile()`.
- **Dependensi**: Model `VillageProfile`.
- **Strategi Caching**: Digempur pengunjung (*Heavy caching*), mewajibkan `Cache::forget` pascamutasi.
- **Strategi File Cleanup**: Diorkestrasi via *Observer* untuk hapus logo usang.

#### 4. `SettingService`
- **Tujuan**: Sistem pengaturan variabel global.
- **Tanggung Jawab Utama**: Pembaruan mode situs (aktif/perbaikan) dan variabel dinamis.
- **Method Inti**: `getValue()`, `updateSettings()`.
- **Dependensi**: Model `Setting`.
- **Strategi Caching**: *Heavy caching* permanen.

---

### B. Content Services

#### 5. `ArticleService`
- **Tujuan**: Pemuatan berita desa.
- **Tanggung Jawab Utama**: Menulis, menerbitkan, dan merevisi berita, menyisipkan foto kover.
- **Method Inti**: `publish()`, `draft()`, `findBySlug()`.
- **Dependensi**: Model `Article`, `ArticleObserver` (untuk Cleanup).
- **Strategi Transaksi DB**: Wajib saat merajut relasi antar Kategori & Artikel.
- **Strategi File Cleanup**: *Observer* menghapus gambar versi lama saat *update/delete*.

#### 6. `AnnouncementService`
- **Tujuan**: Mengeluarkan maklumat seketika.
- **Tanggung Jawab Utama**: Penyebaran pengumuman terstruktur.
- **Method Inti**: `broadcast()`, `revoke()`.
- **Dependensi**: Model `Announcement`.
- **Strategi Caching**: Ter-*cache* pada halaman muka.

#### 7. `AgendaService`
- **Tujuan**: Penjadwalan kalender desa.
- **Tanggung Jawab Utama**: Pengaturan waktu acara lokal.
- **Method Inti**: `schedule()`, `cancel()`.
- **Dependensi**: Model `Agenda`.

---

### C. Media Services

#### 8. `ApparatusService`
- **Tujuan**: Tata laksana aparat pemerintahan.
- **Tanggung Jawab Utama**: Menampilkan bagan struktur organisasi desa.
- **Method Inti**: `appoint()`, `retire()`.
- **Dependensi**: Model `Apparatus`.
- **Strategi File Cleanup**: Foto profil dihapus *Observer* ketika diberhentikan.

#### 9. `GalleryService`
- **Tujuan**: Pusat dokumentasi aset visual.
- **Tanggung Jawab Utama**: Kompresi, pembuatan album foto massal.
- **Method Inti**: `createAlbum()`, `uploadPhoto()`, `removePhoto()`.
- **Dependensi**: Model `GalleryAlbum`, `GalleryPhoto`, plus `GalleryObserver`.
- **Strategi Transaksi DB**: Absolut saat mengunggah foto *batch* ke album.
- **Strategi File Cleanup**: Mutlak dipandu *Observer* (mitigasi diska bocor).

#### 10. `VideoService`
- **Tujuan**: Papan tayangan audiovisual.
- **Tanggung Jawab Utama**: Integrasi sematan YouTube/URL eskternal.
- **Method Inti**: `embed()`, `remove()`.
- **Dependensi**: Model `Video`.

---

### D. Data & Utility Services (Potentials & Data)

#### 11. `UmkmService`
- **Tujuan**: Katalisator pengusaha mikro desa.
- **Tanggung Jawab Utama**: Kurasi dan pendataan etalase UMKM.
- **Method Inti**: `registerUmkm()`, `verifyUmkm()`.
- **Dependensi**: Model `Umkm`, `UmkmObserver`.

#### 12. `TourismService`
- **Tujuan**: Eksposisi destinasi wisata.
- **Tanggung Jawab Utama**: Promosi lokawisata daerah.
- **Method Inti**: `addDestination()`, `closeDestination()`.
- **Dependensi**: Model `Tourism`.

#### 13. `ProductService`
- **Tujuan**: Manajemen komoditas lokal.
- **Tanggung Jawab Utama**: Etalase produk desa.
- **Method Inti**: `listProduct()`, `delistProduct()`.
- **Dependensi**: Model `Product`.

#### 14. `StatisticService`
- **Tujuan**: Demografi penduduk.
- **Tanggung Jawab Utama**: Mengompilasi grafik penduduk, pekerjaan, pendidikan.
- **Method Inti**: `recordStatistic()`, `aggregate()`.
- **Dependensi**: Model `VillageStatistic`.

#### 15. `DocumentService`
- **Tujuan**: Brankas perundang-undangan dan *file* rahasia.
- **Tanggung Jawab Utama**: Perlindungan dan visibilitas unduhan PDF.
- **Method Inti**: `uploadDocument()`, `revokeAccess()`.
- **Dependensi**: Model `Document`, `DocumentObserver`.
- **Strategi Transaksi DB**: Ekstra proteksi transaksi log aktivitas unduh.

#### 16. `ComplaintService`
- **Tujuan**: Sistem penanganan aduan masyarakat.
- **Tanggung Jawab Utama**: Mengurai status, membuat balasan (Histories), mengaitkan foto bukti lampiran.
- **Method Inti**: `submitComplaint()`, `resolve()`, `reject()`.
- **Dependensi**: Model `Complaint`, `ComplaintHistory`, `ComplaintAttachment`.
- **Strategi Transaksi DB**: **Mutlak Wajib** karena penulisan aduan menyuntik minimal dua tabel sekaligus.

#### 17. `WebsiteManagementService`
- **Tujuan**: Presentasi spanduk (Banners), *Contact Messages*, & FAQ.
- **Tanggung Jawab Utama**: Sinkronisasi komponen *Front-End*.
- **Method Inti**: `addBanner()`, `receiveContact()`, `publishFaq()`.
- **Dependensi**: Model `Banner`, `Faq`, `ContactMessage`.
- **Strategi Caching**: Beranda di-*cache* total.
