# 🎨 Design Document — Website Resmi Desa Burujuljaya

> **Status**: Menunggu Review & Approval | Tidak ada kode hingga dokumen ini disetujui.

---

## 1. 🗺️ Sitemap — Halaman Publik

```
/ (Home)
├── /profil
│   ├── /profil/sejarah                → Sejarah Desa
│   ├── /profil/visi-misi              → Visi & Misi
│   ├── /profil/struktur-organisasi    → Struktur Organisasi
│   └── /profil/aparatur               → Aparatur Desa
│
├── /berita
│   ├── /berita                        → Daftar Berita (paginated)
│   ├── /berita/kategori/{slug}        → Berita per Kategori
│   └── /berita/{slug}                 → Detail Berita
│
├── /pengumuman
│   ├── /pengumuman                    → Daftar Pengumuman
│   └── /pengumuman/{slug}             → Detail Pengumuman
│
├── /agenda                            → Kalender & Daftar Agenda
│
├── /galeri
│   ├── /galeri/foto                   → Galeri Foto (per album)
│   ├── /galeri/foto/{slug}            → Detail Album Foto
│   └── /galeri/video                  → Galeri Video
│
├── /potensi
│   ├── /potensi/umkm                  → UMKM Desa
│   ├── /potensi/wisata                → Destinasi Wisata
│   └── /potensi/produk                → Produk Desa
│
├── /statistik                         → Statistik Desa
├── /peta                              → Peta Desa (embed Google Maps)
├── /dokumen                           → Download Dokumen Publik
├── /kontak                            → Halaman Kontak & Form
├── /faq                               → FAQ
└── /pencarian?q={query}               → Hasil Pencarian Global
```

**URL Rules:**
- Semua URL `kebab-case`, tidak ada trailing slash
- Slug digenerate otomatis dari judul (Str::slug)
- Canonical URL di-inject di setiap halaman via middleware
- Friendly 404 page dengan navigasi kembali ke home

---

## 2. 🗺️ Sitemap — Dashboard Admin

```
/admin
├── /admin/dashboard                   → Overview & Statistik

├── /admin/berita
│   ├── /admin/berita                  → Daftar Berita
│   ├── /admin/berita/create           → Buat Berita Baru
│   ├── /admin/berita/{id}/edit        → Edit Berita
│   └── /admin/kategori-berita         → Kelola Kategori Berita

├── /admin/pengumuman
│   ├── /admin/pengumuman              → Daftar Pengumuman
│   ├── /admin/pengumuman/create
│   └── /admin/pengumuman/{id}/edit

├── /admin/agenda
│   ├── /admin/agenda                  → Daftar Agenda
│   ├── /admin/agenda/create
│   └── /admin/agenda/{id}/edit

├── /admin/aparatur
│   ├── /admin/aparatur                → Daftar Aparatur
│   ├── /admin/aparatur/create
│   ├── /admin/aparatur/{id}/edit
│   └── /admin/jabatan                 → Kelola Jabatan/Posisi

├── /admin/galeri
│   ├── /admin/galeri/foto             → Daftar Album
│   ├── /admin/galeri/foto/create      → Buat Album
│   ├── /admin/galeri/foto/{id}        → Kelola Foto dalam Album
│   └── /admin/galeri/video            → Kelola Video

├── /admin/potensi
│   ├── /admin/umkm                    → Kelola UMKM
│   ├── /admin/wisata                  → Kelola Wisata
│   └── /admin/produk                  → Kelola Produk

├── /admin/profil-desa                 → Edit Profil & Informasi Desa
├── /admin/statistik                   → Kelola Data Statistik
├── /admin/dokumen                     → Kelola Dokumen
├── /admin/banner                      → Kelola Hero Banner
├── /admin/faq                         → Kelola FAQ

├── /admin/kontak
│   ├── /admin/kontak                  → Pesan Masuk
│   └── /admin/kontak/pengaturan       → Info Kontak Desa

├── /admin/pengguna
│   ├── /admin/pengguna                → Daftar User
│   ├── /admin/pengguna/create
│   └── /admin/pengguna/{id}/edit

├── /admin/role                        → Kelola Role & Permission (Super Admin)
├── /admin/pengaturan                  → Pengaturan Website Global
├── /admin/log-aktivitas               → Audit Log
└── /admin/backup                      → Backup Database (Super Admin)
```

---

## 3. 👤 User Flow

### 3.1 Guest (Pengunjung)

```
[Buka Website]
      │
      ▼
[Home Page]
      │
      ├──► [Baca Berita] ──► [Detail Berita] ──► [Berita Terkait]
      │
      ├──► [Profil Desa] ──► [Sejarah / Visi Misi / Aparatur]
      │
      ├──► [Pengumuman] ──► [Detail] ──► [Download Lampiran?]
      │
      ├──► [Galeri Foto] ──► [Album] ──► [Lightbox Foto]
      │
      ├──► [UMKM/Wisata] ──► [Detail] ──► [Hubungi via Kontak]
      │
      ├──► [Download Dokumen] ──► [Preview] ──► [Download]
      │
      ├──► [Kontak] ──► [Isi Form] ──► [Submit] ──► [Sukses]
      │
      └──► [FAQ] ──► [Search FAQ]
```

### 3.2 Editor Berita

```
[Login → /login]
      │
      ▼
[Redirect ke /admin/dashboard]
      │
      ├──► [/admin/berita]
      │         ├──► [Create] → [Isi Form + Upload Gambar]
      │         │                  → [Preview] → [Draft / Publish]
      │         └──► [Edit]   → [Ubah Konten] → [Update]
      │
      ├──► [/admin/pengumuman] → [CRUD]
      └──► [/admin/agenda]     → [CRUD]

✗ Tidak bisa: user, role, pengaturan, backup, profil desa, aparatur
```

### 3.3 Operator

```
[Login] → [Dashboard]
      │
      ├──► Semua akses Editor Berita
      ├──► [/admin/aparatur]   → [CRUD Aparatur]
      ├──► [/admin/galeri]     → [CRUD Galeri]
      ├──► [/admin/umkm]       → [CRUD UMKM]
      ├──► [/admin/wisata]     → [CRUD Wisata]
      ├──► [/admin/dokumen]    → [Upload Dokumen]
      └──► [/admin/kontak]     → [Lihat & Balas Pesan]

✗ Tidak bisa: user, role, pengaturan, backup
✗ Tidak bisa DELETE data penting (hanya archive/deactivate)
```

### 3.4 Admin Desa

```
[Login] → [Dashboard]
      │
      ├──► Semua akses Operator
      ├──► [/admin/pengguna]       → [CRUD User (kecuali super-admin)]
      ├──► [/admin/pengaturan]     → [Edit Setting Website]
      ├──► [/admin/profil-desa]    → [Edit Profil Desa]
      ├──► [/admin/statistik]      → [CRUD Statistik]
      ├──► [/admin/banner]         → [CRUD Banner]
      ├──► [/admin/faq]            → [CRUD FAQ]
      └──► [/admin/log-aktivitas]  → [Lihat Log]

✗ Tidak bisa: kelola role, backup database
```

### 3.5 Super Admin

```
[Login] → [Dashboard]
      │
      ├──► Semua akses Admin Desa
      ├──► [/admin/role]    → [CRUD Role & Permission]
      └──► [/admin/backup]  → [Backup & Download DB]
```

### 3.6 Authentication Flow (Semua Role)

```
[GET /login]
      │
      ▼
[Form: Email + Password]
      │
      ├──[Gagal ≤ 4x]──► [Error message, form kembali]
      │
      ├──[Gagal ke-5x]──► [Account lock 15 menit]
      │                       ──► [Notif error jelas]
      │
      └──[Berhasil]──► [2FA aktif?]
                              │
                    ┌─────────┴─────────┐
                   [Ya]               [Tidak]
                    │                   │
              [Input OTP]          [Dashboard]
                    │
              [Verifikasi]
                    │
                    └──► [Dashboard]
```

---

## 4. 📐 Wireframe Low-Fidelity

### 4.1 Home Page (Publik)

```
┌──────────────────────────────────────────────────────────────────┐
│ [LOGO] Desa Burujuljaya  Beranda Profil▼ Berita Pengumuman Kontak│ ← Navbar
└──────────────────────────────────────────────────────────────────┘
┌──────────────────────────────────────────────────────────────────┐
│  ┌────────────────────────────────────────────────────────────┐  │
│  │  HERO SLIDER                                               │  │
│  │  "Selamat Datang di Website Resmi Desa Burujuljaya"       │  │
│  │  Subtitle deskripsi singkat...                            │  │
│  │  [Lihat Profil Desa]  [Hubungi Kami]                      │  │
│  │                           ● ○ ○ ○                         │  │
│  └────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────┘
┌────────────┬────────────┬────────────┬────────────────────────────┐
│ 👥 Penduduk│ 🏘️ Luas Wil│ 🌾 Potensi │ 📋 Layanan                │ ← Stats
│ 12.450 jiwa│  245 Ha    │ Pertanian  │  8 Jenis                  │
└────────────┴────────────┴────────────┴────────────────────────────┘

═══ BERITA TERKINI ══════════════════════════════════════════════════
┌─────────────────────┐  ┌─────────────────────┐  ┌───────────────┐
│ [GAMBAR BERITA]     │  │ [GAMBAR BERITA]     │  │ [GAMBAR BERITA│
│ [Kategori Badge]    │  │ [Kategori Badge]    │  │ [Kategori]    │
│ Judul Berita...     │  │ Judul Berita...     │  │ Judul...      │
│ 12 Jul | 👁 123     │  │ 10 Jul | 👁 89      │  │ 8 Jul | 👁 67 │
└─────────────────────┘  └─────────────────────┘  └───────────────┘
                              [Lihat Semua Berita →]

═══ PENGUMUMAN TERBARU ══════════════════════════════════════════════
┌──────────────────────────────────────────────────────────────────┐
│ 📌 Jadwal Posyandu Bulan Juli 2026                        12 Jul │
│ 📢 Pembukaan Pendaftaran BLT Desa                         10 Jul │
│ 📢 Perbaikan Jalan Desa RT 03                              8 Jul │
└──────────────────────────────────────────────────────────────────┘
                          [Lihat Semua Pengumuman →]

═══ APARATUR DESA ═══════════════════════════════════════════════════
┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌────────┐
│ [FOTO]   │  │ [FOTO]   │  │ [FOTO]   │  │ [FOTO]   │  │ [FOTO] │
│ Kepala   │  │ Sekretaris│  │ Kaur     │  │ Kaur     │  │ Kadus  │
│ Desa     │  │ Desa      │  │ Umum     │  │ Keuangan │  │        │
└──────────┘  └──────────┘  └──────────┘  └──────────┘  └────────┘

═══ POTENSI DESA ════════════════════════════════════════════════════
┌───────────────────────┐      ┌───────────────────────┐
│ 🏪 UMKM DESA          │      │ 🏔️ WISATA              │
│ [Img] [Img] [Img]     │      │ [Img] [Img] [Img]     │
│ XX UMKM Terdaftar     │      │ Y Destinasi Wisata     │
│ [Selengkapnya →]      │      │ [Selengkapnya →]      │
└───────────────────────┘      └───────────────────────┘

═══ GALERI ══════════════════════════════════════════════════════════
┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐
│[FOTO]│ │[FOTO]│ │[FOTO]│ │[FOTO]│ │[FOTO]│ │ +99  │
└──────┘ └──────┘ └──────┘ └──────┘ └──────┘ └──────┘
                        [Lihat Galeri Lengkap →]

═══ PETA DESA ═══════════════════════════════════════════════════════
┌──────────────────────────────────────────────────────────────────┐
│                    [ GOOGLE MAPS EMBED ]                         │
│                         (400px height)                           │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│ FOOTER                                                           │
│ [Logo]  Tentang │ Layanan │ Informasi    [FB][IG][YT]            │
│ Desa Burujuljaya                                                 │
│ Kec. X, Kab. Y, Jawa Barat                                       │
│ © 2026 Pemerintah Desa Burujuljaya                              │
└──────────────────────────────────────────────────────────────────┘
```

### 4.2 Berita — List

```
[Home > Berita]                                           ← Breadcrumb
═══════════════════════════════════════════════════════════
  BERITA DESA
  Informasi terkini seputar kegiatan Desa Burujuljaya
═══════════════════════════════════════════════════════════

  [Semua] [Pemerintahan] [Pembangunan] [Sosial] [Kegiatan] ← Filter Tab

  [🔍 Cari berita...                                     ]

┌──────────────────────────────────┐  ┌─────────────────────────┐
│ [GAMBAR BESAR - FEATURED]        │  │ BERITA POPULER          │
│ [Kategori Badge]                 │  │ 1. Judul berita (234)   │
│ Judul Berita Unggulan Panjang   │  │ 2. Judul berita (189)   │
│ Excerpt singkat 2 baris max...  │  │ 3. Judul berita (145)   │
│ 📅 12 Jul 2026 | ⏱ 5 mnt | 👁234│  │                         │
└──────────────────────────────────┘  │ KATEGORI                │
                                      │ • Pemerintahan (12)     │
┌──────────┐ ┌──────────┐ ┌────────┐  │ • Pembangunan (8)       │
│ [GAMBAR] │ │ [GAMBAR] │ │[GAMBAR]│  │ • Sosial (15)           │
│ Kategori │ │ Kategori │ │Kategori│  │ • Kegiatan (20)         │
│ Judul... │ │ Judul... │ │Judul.. │  │                         │
│ Tgl | 👁  │ │ Tgl | 👁  │ │ Tgl|👁 │  │ ARSIP                   │
└──────────┘ └──────────┘ └────────┘  │ • Juli 2026 (12)       │
                                      │ • Juni 2026 (8)         │
[← Prev]  [1] [2] [3] ... [10]  [→]  └─────────────────────────┘
```

### 4.3 Detail Berita

```
[Home > Berita > Judul Artikel]

┌────────────────────────────────────┐  ┌──────────────────────────┐
│ [Kategori Badge]                   │  │ BAGIKAN                  │
│ Judul Berita yang Lengkap dan     │  │ [FB] [TW] [WA] [📋Copy]  │
│ Informatif                         │  │                          │
│                                    │  │ BERITA TERKAIT           │
│ 👤 Nama Penulis  📅 12 Juli 2026   │  │ ┌──────────────────────┐ │
│ ⏱ 5 menit baca  👁 234 kali       │  │ │[img] Judul berita... │ │
│                                    │  │ └──────────────────────┘ │
│ ─────────────────────────────────  │  │ ┌──────────────────────┐ │
│ [ GAMBAR FEATURED — FULL WIDTH ]   │  │ │[img] Judul berita... │ │
│ ─────────────────────────────────  │  │ └──────────────────────┘ │
│                                    │  │                          │
│ Isi konten artikel...              │  │ KATEGORI LAINNYA         │
│                                    │  │ • Pemerintahan           │
│ [Subheading]                       │  │ • Pembangunan            │
│ Paragraf berita lanjutan...        │  │ • Sosial                 │
│                                    │  └──────────────────────────┘
│ [ GAMBAR DALAM KONTEN ]            │
│                                    │
│ Paragraf lanjutan...               │
│                                    │
│ Tags: [desa] [kegiatan] [2026]     │
│                                    │
│ ─────────────────────────────────  │
│ [← Berita Sebelumnya]  [Berikutnya→]│
└────────────────────────────────────┘
```

### 4.4 Admin — Dashboard

```
┌──────────────────────────────────────────────────────────────────┐
│ [≡] Desa Burujuljaya Admin       [🔔3]  [👤 Admin Desa]  [Logout]│ ← Topbar
└──────────────────────────────────────────────────────────────────┘
┌──────────────┬───────────────────────────────────────────────────┐
│              │                                                   │
│  🏠 Dashboard │  Dashboard                                        │
│              │  Selamat datang, [Nama]!  📅 23 Juli 2026         │
│  KONTEN      │                                                   │
│  📰 Berita   │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────┐ │
│  📢 Pengmman │  │ 📰 Berita │ │ 📢 Pengmn│ │ 👥 User  │ │  👁  │ │
│  📅 Agenda   │  │    48    │ │    12    │ │    8     │ │ 1.2K │ │
│              │  │ ↑3 baru  │ │ ↑1 baru  │ │          │ │ /hari│ │
│  DATA DESA   │  └──────────┘ └──────────┘ └──────────┘ └──────┘ │
│  👤 Aparatur │                                                   │
│  🖼️ Galeri   │  ┌──────────────────────────┐  ┌───────────────┐  │
│  🎬 Video    │  │  GRAFIK KUNJUNGAN        │  │ BERITA TERBARU│  │
│  📊 Statistik│  │  [Line Chart — 30 hari]  │  │               │  │
│              │  │                          │  │ • Judul 1     │  │
│  POTENSI     │  │                          │  │   12 Jul      │  │
│  🏪 UMKM     │  └──────────────────────────┘  │ • Judul 2     │  │
│  🏔️ Wisata   │                                │   11 Jul      │  │
│  🛍️ Produk   │  ┌──────────────────────────┐  │ • Judul 3     │  │
│              │  │  PESAN MASUK (3 baru)    │  │   10 Jul      │  │
│  LAYANAN     │  │  Budi | Pertanyaan | Tgl │  └───────────────┘  │
│  📄 Dokumen  │  │  Siti | Pengaduan  | Tgl │                     │
│  📩 Kontak   │  │  [Lihat Semua →]         │  ┌───────────────┐  │
│              │  └──────────────────────────┘  │ LOG AKTIVITAS │  │
│  WEBSITE     │                                │ • Admin edit  │  │
│  🖼️ Banner   │                                │   berita      │  │
│  ❓ FAQ       │                                │ • Operator    │  │
│  ⚙️ Setting  │                                │   tambah foto │  │
│              │                                └───────────────┘  │
│  SISTEM      │                                                   │
│  👥 Pengguna │                                                   │
│  🔑 Role     │                                                   │
│  📋 Log      │                                                   │
│  💾 Backup   │                                                   │
│              │                                                   │
│ ─────────── │                                                   │
│ [👤] Nama   │                                                   │
│   admin@... │                                                   │
└──────────────┴───────────────────────────────────────────────────┘
```

### 4.5 Admin — Data Table (Contoh: Berita)

```
┌──────────────┬───────────────────────────────────────────────────┐
│  SIDEBAR     │  Kelola Berita                                     │
│              │                                                   │
│              │  [+ Tambah Berita]                      [Export▼] │
│              │                                                   │
│              │  ┌─────────────────────────────────────────────┐  │
│              │  │ [🔍 Cari...]  [▼ Kategori]  [▼ Status]  [↺] │  │
│              │  └─────────────────────────────────────────────┘  │
│              │                                                   │
│              │  ┌─────────────────────────────────────────────┐  │
│              │  │ □ │Gambar │ Judul    │ Kat │Status│ Tgl │Aksi│  │
│              │  ├─────────────────────────────────────────────┤  │
│              │  │ □ │[img]  │ Judul 1  │ Pem │ ✅   │12Jul│✏🗑│  │
│              │  │ □ │[img]  │ Judul 2  │ Sos │ 📝   │11Jul│✏🗑│  │
│              │  │ □ │[img]  │ Judul 3  │ Pem │ ✅   │10Jul│✏🗑│  │
│              │  │ □ │[img]  │ Judul 4  │ Ban │ 🗄️   │09Jul│✏🗑│  │
│              │  ├─────────────────────────────────────────────┤  │
│              │  │ Menampilkan 1–10 dari 48       [1][2][3][→] │  │
│              │  └─────────────────────────────────────────────┘  │
└──────────────┴───────────────────────────────────────────────────┘
```

### 4.6 Admin — Form Create/Edit (Contoh: Berita)

```
┌──────────────┬───────────────────────────────────────────────────┐
│  SIDEBAR     │  Tambah Berita                                     │
│              │  [← Kembali ke Daftar]                            │
│              │                                                   │
│              │  ┌────────────────────────────────┐  ┌──────────┐ │
│              │  │  INFORMASI BERITA               │  │ SIDEBAR  │ │
│              │  │                                │  │ PUBLISH  │ │
│              │  │  Judul *                       │  │          │ │
│              │  │  [______________________________] │  │ Status   │ │
│              │  │                                │  │ [▼Draft] │ │
│              │  │  Slug (auto)                   │  │          │ │
│              │  │  [______________________________] │  │ Kategori │ │
│              │  │                                │  │ [▼Pilih] │ │
│              │  │  Excerpt *                     │  │          │ │
│              │  │  [______________________________] │  │ Tgl Terbit│ │
│              │  │  [______________________________] │  │ [_______]│ │
│              │  │                                │  │          │ │
│              │  │  Konten *                      │  │ Featured?│ │
│              │  │  [B][I][U][H1][H2][•][🖼️][🔗]   │  │ [○]      │ │
│              │  │  ┌──────────────────────────┐  │  │          │ │
│              │  │  │                          │  │  │ GAMBAR   │ │
│              │  │  │  TipTap Rich Text Editor │  │  │ UTAMA    │ │
│              │  │  │                          │  │  │          │ │
│              │  │  │  (min. 300px height)     │  │  │[DROP IMG]│ │
│              │  │  └──────────────────────────┘  │  │          │ │
│              │  │                                │  │ [Preview]│ │
│              │  │  ─── SEO ──────────────────    │  │          │ │
│              │  │  Meta Title                    │  └──────────┘ │
│              │  │  [______________________________] │             │
│              │  │  Meta Description              │             │
│              │  │  [______________________________] │             │
│              │  └────────────────────────────────┘             │
│              │                                                   │
│              │        [Simpan Draft]    [Terbitkan Sekarang]     │
└──────────────┴───────────────────────────────────────────────────┘
```

### 4.7 Login Page

```
┌──────────────────────────────────────────────────────────────────┐
│  [Gambar/Ilustrasi Desa]     │   ┌──────────────────────────┐   │
│                              │   │  [🏛️ LOGO DESA]           │   │
│  Foto atau ilustrasi         │   │  Desa Burujuljaya         │   │
│  desa yang menarik           │   │  Portal Administrasi      │   │
│                              │   │                           │   │
│  (Split layout, 50/50)       │   │  ──────────────────────   │   │
│                              │   │                           │   │
│                              │   │  Alamat Email             │   │
│                              │   │  [_____________________ ] │   │
│                              │   │                           │   │
│                              │   │  Kata Sandi               │   │
│                              │   │  [___________________][👁]│   │
│                              │   │                           │   │
│                              │   │  [□] Ingat Saya           │   │
│                              │   │             [Lupa Sandi?] │   │
│                              │   │                           │   │
│                              │   │  [      MASUK           ] │   │
│                              │   │                           │   │
│                              │   │  ──────────────────────   │   │
│                              │   │  © 2026 Desa Burujuljaya  │   │
│                              │   └──────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
```

---

## 5. 🧩 Reusable Vue Components

### 5.1 Public Components (`resources/js/components/public/`)

| Komponen | Props | Deskripsi |
|---|---|---|
| `PublicNavbar.vue` | `villageName`, `logo`, `menus[]` | Navbar responsif + dropdown + mobile hamburger |
| `PublicFooter.vue` | `village`, `links[]`, `socials[]` | Footer dengan info, link, sosmed |
| `HeroSlider.vue` | `slides[]`, `autoplay`, `interval` | Hero banner auto-slide |
| `ArticleCard.vue` | `article`, `variant: 'grid'\|'list'\|'featured'` | Kartu berita multi-variant |
| `AnnouncementItem.vue` | `announcement` | Baris item pengumuman + pin badge |
| `ApparatusCard.vue` | `apparatus` | Kartu foto + nama + jabatan aparatur |
| `GalleryGrid.vue` | `photos[]`, `columns` | Grid foto + lightbox |
| `VideoCard.vue` | `video` | Embed YouTube/video dengan thumbnail |
| `StatsCounter.vue` | `stats[]` | Counter animasi scroll-triggered |
| `OrgChart.vue` | `nodes[]` | Visualisasi struktur organisasi SVG |
| `MapEmbed.vue` | `lat`, `lng`, `zoom`, `title` | Google Maps iframe embed |
| `SearchBar.vue` | `placeholder`, `route` | Input pencarian publik |
| `SectionHeader.vue` | `title`, `subtitle`, `link`, `linkText` | Header tiap section home |
| `UmkmCard.vue` | `umkm` | Kartu UMKM (foto + nama + kategori) |
| `TourismCard.vue` | `tourism` | Kartu destinasi wisata |
| `DocumentItem.vue` | `document` | Item dokumen dengan ikon tipe file + download |
| `ContactForm.vue` | — | Form kontak dengan validasi real-time |
| `FaqAccordion.vue` | `faqs[]` | FAQ accordion dengan animasi |
| `ShareButtons.vue` | `url`, `title` | Tombol share FB/Twitter/WA/Copy |

### 5.2 Admin Components (`resources/js/components/admin/`)

| Komponen | Props | Deskripsi |
|---|---|---|
| `AdminSidebar.vue` | `user`, `permissions[]` | Sidebar navigasi + collapsible + permission-aware |
| `AdminTopbar.vue` | `user`, `notifications[]` | Topbar + notif dropdown + user menu |
| `DataTable.vue` | `columns[]`, `data`, `loading`, `meta` | Tabel data reusable + sort + loading state |
| `StatsCard.vue` | `title`, `value`, `icon`, `trend`, `color` | Kartu statistik dashboard |
| `ActivityFeed.vue` | `logs[]` | Feed log aktivitas terakhir |
| `RichTextEditor.vue` | `modelValue`, `config?` | TipTap WYSIWYG wrapper |
| `ImageUploader.vue` | `modelValue`, `accept`, `maxSizeMb` | Upload foto tunggal + preview + validasi |
| `MultiImageUploader.vue` | `modelValue[]`, `maxFiles` | Upload banyak foto + drag-drop |
| `FileUploader.vue` | `modelValue`, `accept`, `maxSizeMb` | Upload dokumen (PDF, DOC, dll) |
| `StatusBadge.vue` | `status` | Badge warna sesuai status |
| `ConfirmModal.vue` | `open`, `title`, `message`, `danger` | Modal konfirmasi aksi destruktif |
| `FilterBar.vue` | `filters[]` | Bar filter di atas tabel (search + select) |
| `BulkActionBar.vue` | `selected[]`, `actions[]` | Bar aksi massal saat item dicentang |
| `ChartLine.vue` | `data`, `labels[]`, `title` | Line chart kunjungan (Chart.js wrapper) |
| `ChartBar.vue` | `data`, `labels[]`, `title` | Bar chart statistik |
| `NotificationPanel.vue` | `notifications[]` | Dropdown panel notifikasi |

### 5.3 Shared Components (`resources/js/components/shared/`)

| Komponen | Props | Slot | Deskripsi |
|---|---|---|---|
| `Breadcrumb.vue` | `items[]` | — | Navigasi breadcrumb SEO-friendly |
| `Pagination.vue` | `meta`, `only[]?` | — | Inertia-aware pagination |
| `EmptyState.vue` | `title`, `message`, `icon` | `action` | State kosong reusable |
| `LoadingSkeleton.vue` | `type`, `count` | — | Skeleton loading per komponen |
| `UserAvatar.vue` | `user`, `size` | — | Avatar foto atau inisial fallback |
| `PermissionGate.vue` | `permission\|role` | `default`, `fallback` | Conditional render berbasis permission |
| `AppPagination.vue` | `links[]`, `meta` | — | Pagination dari Inertia meta |

### 5.4 UI Primitives — Sudah Ada (Reka UI)

Komponen ini **tidak perlu dibuat ulang**, langsung digunakan dari starter kit:

`Button`, `Input`, `Label`, `Select`, `Checkbox`, `Dialog`, `Badge`, `Card`, `Separator`, `Tooltip`, `DropdownMenu`, `Skeleton`, `Spinner`, `Alert`, `Sheet` (drawer), `Sonner` (toast), `InputOtp`

---

## 6. 📐 Layouts

### `layouts/PublicLayout.vue`
- **Digunakan oleh**: Semua halaman publik (`/`, `/berita`, `/profil`, dst.)
- **Struktur**: `PublicNavbar` → `<main><slot /></main>` → `PublicFooter`
- **Fitur**:
  - Meta tags (title, description, OG) dari page props
  - Scroll-to-top saat navigasi
  - Active link detection di navbar
  - Responsif full: mobile-first

### `layouts/AdminLayout.vue`
- **Digunakan oleh**: Semua halaman `/admin/*`
- **Struktur**: `AdminSidebar` (fixed left) + `div.flex-1` → `AdminTopbar` + `<main><slot /></main>`
- **Fitur**:
  - Auto-redirect ke `/login` jika tidak terautentikasi
  - Flash toast dari `$page.props.flash`
  - Sidebar collapsible (icon-only) di desktop
  - Sidebar overlay + close button di mobile
  - Permission check per menu item

### `layouts/AuthLayout.vue` *(sudah ada, akan dimodifikasi)*
- **Digunakan oleh**: `/login`, `/forgot-password`, `/reset-password`
- **Modifikasi**: Ganti branding generik dengan logo + nama desa, split layout

---

## 7. 🧭 Navigation Structure

### 7.1 Public Navbar (Desktop)

```
[LOGO + Nama Desa]    Beranda   Profil ▾   Berita   Pengumuman   Galeri ▾   Potensi ▾   Kontak   [🔍]

Profil ▾:                    Galeri ▾:              Potensi ▾:
  • Sejarah Desa               • Galeri Foto           • UMKM Desa
  • Visi & Misi                • Galeri Video          • Destinasi Wisata
  • Struktur Organisasi                                • Produk Desa
  • Aparatur Desa
```

**Mobile (Hamburger ≡):** Slide-in dari kiri, semua link + accordion untuk dropdown, overlay backdrop.

### 7.2 Admin Sidebar

```
[LOGO] Desa Burujuljaya
───────────────────────
🏠  Dashboard

▼ KONTEN
    📰  Berita
    📢  Pengumuman
    📅  Agenda

▼ DATA DESA
    👤  Aparatur Desa
    🖼️  Galeri Foto
    🎬  Video
    📋  Profil Desa
    📊  Statistik

▼ POTENSI
    🏪  UMKM
    🏔️  Wisata
    🛍️  Produk

▼ LAYANAN
    📄  Dokumen
    📩  Kontak & Pesan

▼ WEBSITE
    🖼️  Banner
    ❓  FAQ
    ⚙️  Pengaturan

▼ SISTEM  ← [admin-desa ke atas]
    👥  Pengguna
    🔑  Role  ← [super-admin only]
    📋  Log Aktivitas
    💾  Backup  ← [super-admin only]

───────────────────────
[👤 Avatar] Nama Admin
            admin@burujuljaya.id
```

**Behavior sidebar:**
- Desktop: dapat di-collapse ke mode icon-only (lebar 64px)
- Mobile: hidden default, toggle via hamburger, overlay backdrop

---

## 8. 🎨 Design System

### 8.1 Color Palette

```
PRIMARY — Hijau Pemerintah
  green-50:  #f0fdf4   → Background ringan, highlight area
  green-100: #dcfce7   → Badge background
  green-600: #16a34a   → Warna utama (button, link, aktif)
  green-700: #15803d   → Hover state
  green-900: #14532d   → Dark variant

SECONDARY — Biru Nasional
  blue-50:   #eff6ff   → Background info
  blue-600:  #2563eb   → Secondary button, info badge
  blue-700:  #1d4ed8   → Hover

NEUTRAL — Gray
  gray-50:   #f9fafb   → Page background
  gray-100:  #f3f4f6   → Card bg, sidebar bg
  gray-200:  #e5e7eb   → Border, divider
  gray-400:  #9ca3af   → Placeholder, muted
  gray-600:  #4b5563   → Secondary text
  gray-700:  #374151   → Body text
  gray-900:  #111827   → Heading

SEMANTIC
  Success:   green-600 #16a34a
  Warning:   amber-600 #d97706
  Danger:    red-600   #dc2626
  Info:      blue-600  #2563eb

AKSEN
  Gold:      #f59e0b   → Featured, pin badge, highlight
  White:     #ffffff   → Card, nav, modal background
```

### 8.2 Typography

```
Font Family: 'Instrument Sans', sans-serif   (sudah via Bunny Fonts)

Scale:
  text-xs   : 12px/16px  → Caption, label micro
  text-sm   : 14px/20px  → Body small, table cell, badge
  text-base : 16px/24px  → Body default (paragraf)
  text-lg   : 18px/28px  → Body large, subheading kecil
  text-xl   : 20px/28px  → Subheading section
  text-2xl  : 24px/32px  → Heading section
  text-3xl  : 30px/36px  → Heading halaman (H1 desktop)
  text-4xl  : 36px/40px  → Hero title
  text-5xl  : 48px/1     → Large hero (desktop only)

Weight:
  font-normal   : 400 → Paragraf, body
  font-medium   : 500 → Label, nav item, button
  font-semibold : 600 → Subheading, badge text
  font-bold     : 700 → Heading H1–H3, title

Rules:
  • 1 H1 per halaman, text-3xl md:text-4xl, font-bold
  • H2 section: text-2xl, font-semibold
  • H3 sub-section: text-xl, font-semibold
  • Body: text-base, text-gray-700
  • Caption/meta: text-sm, text-gray-500
```

### 8.3 Spacing

```
Menggunakan Tailwind scale standar.
Prinsip:
  • Intra-komponen : p-3 / p-4 (12–16px)
  • Antar-komponen : gap-4 / gap-6 (16–24px)
  • Section padding: py-12 md:py-16 (48–64px)
  • Container      : max-w-7xl mx-auto px-4 md:px-6
  • Prose content  : max-w-3xl (untuk artikel)
```

### 8.4 Border Radius

```
  rounded-sm  : 4px  → Badge kecil
  rounded-md  : 6px  → Input, button
  rounded-lg  : 8px  → Card standar
  rounded-xl  : 12px → Card besar, galeri item
  rounded-2xl : 16px → Modal, hero card
  rounded-full: 9999 → Avatar, pill badge, toggle switch
```

### 8.5 Shadows

```
  shadow-sm : subtle → Input focus ring
  shadow    : light  → Card default
  shadow-md : medium → Card hover state
  shadow-lg : large  → Dropdown, modal
  shadow-xl : heavy  → Sidebar fixed
```

### 8.6 Icons

```
  Library : Lucide Icons (@lucide/vue) — sudah terinstall
  Sizes   :
    w-4 h-4 (16px) → Inline icon dalam text/badge
    w-5 h-5 (20px) → Button icon, nav icon
    w-6 h-6 (24px) → Section icon, card icon
    w-8 h-8 (32px) → Empty state, feature icon
    w-12 h-12      → Hero icon, large feature

  Aturan: hanya Lucide, tidak mix library lain.
```

### 8.7 Button Variants

| Variant | Styling | Penggunaan |
|---|---|---|
| `primary` | `bg-green-600 text-white hover:bg-green-700` | Aksi utama (Submit, Publish) |
| `secondary` | `border border-gray-300 bg-white text-gray-700 hover:bg-gray-50` | Aksi sekunder |
| `danger` | `bg-red-600 text-white hover:bg-red-700` | Delete, aksi berbahaya |
| `ghost` | `text-gray-700 hover:bg-gray-100` | Aksi tersier, toolbar |
| `link` | `text-green-600 underline-offset-4 hover:underline` | Link dalam teks |

**Sizes:** `sm` (h-8), `md` (h-10, default), `lg` (h-11), `icon` (h-10 w-10)

**States:** Default → Hover → Focus (ring-2 ring-green-500 ring-offset-2) → Active → Disabled (opacity-50 cursor-not-allowed) → Loading (spinner inline)

### 8.8 Form & Input

```
Base input    : h-10 rounded-md border border-gray-300 px-3 text-sm bg-white
Focus         : border-green-500 ring-1 ring-green-500 outline-none
Error state   : border-red-500 ring-1 ring-red-500
Disabled      : bg-gray-50 opacity-60 cursor-not-allowed
Label         : text-sm font-medium text-gray-700, mb-1.5
Required mark : span.text-red-500 ml-1 → *
Error message : text-sm text-red-600 mt-1 flex items-center gap-1
Helper text   : text-sm text-gray-500 mt-1
```

### 8.9 Table

```
Container  : overflow-hidden rounded-xl border border-gray-200 shadow-sm
Header     : bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4
Row        : bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors
Cell       : py-3 px-4 text-sm text-gray-900
Action col : flex items-center gap-2, right-aligned
Mobile     : horizontal scroll dengan overflow-x-auto wrapper
Empty      : gunakan EmptyState component, bukan tabel kosong
Loading    : skeleton rows (3–5), bukan full-page spinner
```

### 8.10 Card

```
Public Article Card:
  • Gambar: aspect-video object-cover rounded-t-xl
  • Badge kategori: absolute top-3 left-3, pill
  • Judul: font-semibold line-clamp-2
  • Meta: flex text-sm text-gray-500 gap-3
  • Hover: hover:shadow-md hover:-translate-y-0.5 transition-all

Admin Content Card:
  • bg-white rounded-xl border border-gray-100 shadow-sm p-6
  • Header: pb-4 border-b mb-4 flex justify-between items-center
```

### 8.11 Modal

```
Overlay   : fixed inset-0 bg-black/50 backdrop-blur-sm z-50
Container : bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4 p-6
Header    : text-lg font-semibold mb-4 flex justify-between items-center
Footer    : mt-6 flex justify-end gap-3
Animation : data-[state=open]:animate-in data-[state=closed]:animate-out (Reka Dialog)
Close     : tombol X + tekan Escape + click overlay
```

### 8.12 Toast

```
Library   : Vue Sonner (sudah ada)
Position  : top-right
Duration  : 4000ms (success/info), 6000ms (error)

toast.success('Berita berhasil diterbitkan')
toast.error('Gagal menyimpan data. Coba lagi.')
toast.warning('Sesi Anda akan segera berakhir.')
toast.info('Data sedang diproses...')
```

### 8.13 Status Badges

```
Artikel/Pengumuman:
  published  → bg-green-100 text-green-700  [✓ Diterbitkan]
  draft      → bg-yellow-100 text-yellow-700 [✎ Draft]
  archived   → bg-gray-100 text-gray-600    [⊠ Diarsipkan]

User:
  active     → bg-green-100 text-green-700  [Aktif]
  inactive   → bg-red-100 text-red-700      [Nonaktif]

Agenda:
  upcoming   → bg-blue-100 text-blue-700    [Akan Datang]
  ongoing    → bg-green-100 text-green-700  [Berlangsung]
  done       → bg-gray-100 text-gray-600    [Selesai]
```

---

## 9. 📡 Strategi Pengambilan Data

### 9.1 Prinsip

- Semua data halaman dikirim via **Inertia props** dari controller
- Tidak ada REST API terpisah untuk page rendering
- AJAX hanya untuk: search autocomplete, upload file, increment views
- **Shared props** (selalu tersedia di semua halaman):

```
auth.user           → User yang login (null jika guest)
auth.permissions[]  → Array nama permission user
village.name        → Nama desa
village.logo        → URL logo
village.address     → Alamat desa
flash.success       → Flash success message
flash.error         → Flash error message
```

### 9.2 Mapping Halaman → Strategi Data

| Halaman | Strategy | Detail |
|---|---|---|
| **Home** | Eager load semua | Banner(3) + Berita terbaru(6) + Pengumuman terpinned(3) + Aparatur(5) + Stats + Galeri(6) — 1 controller, query dioptimasi dengan select() |
| **Berita (list)** | Paginated + query string | Filter kategori, search, page via `?kategori=&q=&page=` — Inertia `only: ['articles']` saat filter |
| **Detail Berita** | Eager load + async view count | Artikel + related(3). View count di-increment via queued job (non-blocking) |
| **Galeri Foto** | Album eager, foto lazy | Album list di halaman utama. Foto per album di-load saat album diklik |
| **Aparatur** | Eager grouped | Query dengan `with('position')`, grouped by divisi di controller |
| **Dashboard Admin** | Mixed: eager + deferred | Stats cards eager. Chart data via `Inertia::defer()` untuk performa awal |
| **Admin List Pages** | Server-side paginated | Filter, sort, search semua via query string. Partial reload `only: ['items']` |
| **Admin Form Create** | Eager options | Dropdown options (kategori, jabatan, dll) eager |
| **Admin Form Edit** | Eager: model + options | `$article->load(...)` + semua dropdown options |

### 9.3 Inertia Features yang Digunakan

| Feature | Digunakan di |
|---|---|
| `useForm()` | Semua form admin (CSRF, loading, error otomatis) |
| `router.visit()` | Navigasi programmatic setelah submit |
| `router.reload({ only })` | Refresh data parsial (setelah delete tanpa reload penuh) |
| `Inertia::defer()` | Dashboard chart data (non-blocking) |
| `preserveScroll: true` | Filter/sort tabel, scroll tetap di posisi |
| `preserveState: true` | Form yang tetap di halaman setelah validasi error |

### 9.4 Caching Strategy

```
Public data (Laravel Cache + cache tags):
  settings / config desa : 24 jam  → flush saat setting diubah
  statistik desa          : 6 jam   → flush saat statistik diubah
  aparatur                : 1 jam   → flush saat aparatur diubah
  berita terbaru (home)   : 15 mnt  → flush saat artikel dipublish
  home page aggregate     : 10 mnt  → flush saat ada konten baru

Admin panel: TIDAK di-cache, selalu fresh

Cache driver: database (default) — upgrade ke Redis jika beban tinggi
```

---

## ✅ Checklist Self-Review Design Document

- [x] Sitemap publik lengkap dengan URL structure yang konsisten
- [x] Sitemap admin dengan hierarki yang jelas
- [x] User flow untuk semua 5 role + auth flow
- [x] Wireframe ASCII untuk Home, List, Detail, Form, Dashboard, Login
- [x] 19 public components + 16 admin components + 7 shared components terdefinisi
- [x] Props tiap komponen terdefinisi (tidak ada komponen tanpa interface jelas)
- [x] 3 layout dengan use case yang tidak tumpang tindih
- [x] Navigation publik + admin sidebar lengkap
- [x] Color palette berbasis identitas pemerintah Indonesia
- [x] Typography scale konsisten
- [x] Semua komponen UI (button, form, table, card, modal, toast, badge) terdefinisi
- [x] Strategi data per halaman terdefinisi
- [x] Caching strategy terdefinisi
- [x] Shared Inertia props terdefinisi
- [x] Tidak ada kode yang ditulis

---

> *Setelah dokumen ini disetujui, tahap berikutnya adalah: Database Design & Migration.*
