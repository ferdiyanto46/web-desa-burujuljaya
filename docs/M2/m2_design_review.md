# Design Review — M2 Foundation
## Senior Software Architect / Database Engineer Perspective

> Scope: `village_profiles`, `settings`, dan keputusan arsitektur turunannya.
> Status: **Pre-implementation** — tidak ada kode yang dihasilkan.

---

## 1. Normalisasi: `mission` JSON vs Tabel `village_missions`

### Evaluasi JSON (pendekatan sebelumnya)

**Kelebihan:**
- Implementasi cepat — satu kolom, tidak ada join
- Data selalu dibaca sekaligus, tidak ada N+1
- Urutan terjaga natural di array JSON

**Kekurangan kritis:**
- **Reordering**: Drag-and-drop di admin panel membutuhkan deserialize seluruh array → reorder → serialize ulang. Tidak atomik, rawan race condition
- **CRUD individual**: Hapus satu misi = manipulasi JSON array di PHP/MySQL. Verbose dan error-prone
- **Tidak ada metadata per misi**: Jika suatu saat dibutuhkan `is_active`, `period`, atau `category` per misi → refactor mahal
- **Testing**: Factory dan unit test untuk JSON arrays lebih rumit dibanding Eloquent rows

### Evaluasi Tabel `village_missions` Terpisah

**Kelebihan:**
- Kolom `sort_order` → reorder adalah `UPDATE SET sort_order = X` yang atomik
- CRUD setiap misi adalah operasi baris standar: `create()`, `delete()`, `update()`
- Admin panel: standard sortable list component, tidak perlu JSON serializer
- Dapat ditambahkan metadata tanpa refactor schema
- Natural Eloquent relationship: `$profile->missions()->ordered()->get()`

**Kekurangan:**
- Satu tabel tambahan (tiny: ~5–10 baris)
- Satu join tambahan untuk halaman publik (diabaikan karena di-cache)

### ✅ Rekomendasi: Tabel `village_missions` Terpisah

**Alasan utama**: Admin panel yang baik untuk konten pemerintahan **pasti** membutuhkan reorder misi. Dengan JSON ini tidak bisa dilakukan secara clean. Overhead tabel ~5–10 baris adalah nol secara performa. Dalam 10 tahun, jika ada kebutuhan metadata per misi, refactor dari JSON ke tabel akan sangat mahal.

**Trade-off yang diterima**: Satu join tambahan untuk halaman profil publik — diabaikan karena data ini selalu di-cache setelah query pertama.

---

## 2. Hero Image: `village_profiles` vs Tabel `banners`

### Masalah Desain Sebelumnya

Kolom `hero_image VARCHAR(500)` di `village_profiles` hanya mendukung **satu gambar statis**. Ini tidak memadai bahkan untuk website desa sekalipun.

### Mengapa Perlu Tabel Terpisah

Website desa yang profesional membutuhkan:
- **Slider homepage** dengan 3–5 gambar berganti otomatis
- **`start_at` / `end_at`**: banner kampanye yang aktif hanya selama periode tertentu
- **`sort_order`**: urutan banner dapat diatur admin
- **`is_active`**: banner bisa dinonaktifkan sementara tanpa dihapus

### ✅ Rekomendasi: Hapus `hero_image` dari `village_profiles`

`village_profiles` hanya berisi **data identitas institusional yang statis** (nama, alamat, koordinat, logo). Banner/slider adalah konten dinamis dengan lifecycle sendiri.

Tabel `banners` sudah ada di roadmap **M10**. `hero_image` di M2 adalah duplikasi yang tidak perlu.

**Trade-off yang diterima**: Halaman publik tidak bisa menampilkan slider sampai M10 selesai. Gunakan logo desa sebagai fallback sementara.

---

## 3. Penamaan Kolom

| Kolom Saat Ini | Masalah | Rekomendasi | Alasan |
|---|---|---|---|
| `logo` | Ambigu: data binary, URL, atau path? | `logo_path` | Eksplisit bahwa ini adalah path di storage, bukan binary/URL eksternal |
| `website` | Tidak jelas apakah domain atau URL lengkap | `website_url` | Eksplisit URL. Mencegah developer bingung soal format |
| `phone` | Dalam konteks tabel ini sudah cukup jelas | `phone` | **Pertahankan** |
| `email` | Sudah jelas dalam konteks ini | `email` | **Pertahankan** |
| `address` | Sudah jelas | `address` | **Pertahankan** |
| `area_hectares` | Sudah sangat eksplisit | `area_hectares` | **Pertahankan** |

### 🔴 Bug Nyata: `longitude DECIMAL(10,7)`

`DECIMAL(M, D)` = M total digit, D di belakang koma. `DECIMAL(10,7)` menyisakan `10 - 7 = 3` digit sebelum koma.

Longitude global berkisar -180 hingga +180 = **4 digit** (termasuk tanda negatif). Gunakan `DECIMAL(11,7)` agar aman secara global dan terstandar.

**Latitude tetap** `DECIMAL(10,7)` karena nilainya -90 hingga +90 (2–3 digit).

---

## 4. Tabel Settings — Evaluasi Kolom Tambahan

### `sort_order SMALLINT UNSIGNED DEFAULT 0` — ✅ TAMBAHKAN

Admin UI menampilkan daftar settings per group. Tanpa `sort_order`, urutan tidak deterministik antar deployment. Developer bisa mengontrol urutan tampil secara eksplisit.

### `is_editable BOOLEAN DEFAULT TRUE` — ✅ TAMBAHKAN

Beberapa settings bersifat sistem-level (contoh: `app.installed_at`, konfigurasi Artisan-only). `is_editable = false` menyebabkan form input di-disable di UI. Lebih clean daripada permission check kompleks per-field.

### `is_encrypted BOOLEAN DEFAULT FALSE` — ✅ TAMBAHKAN (Keamanan)

Settings sensitif: SMTP password, Google Maps API key, WhatsApp Business token harus di-encrypt at rest. Flag ini memberitahu `SettingService` mana yang perlu `Crypt::encryptString()` sebelum simpan dan `Crypt::decryptString()` saat baca. **Ini kebutuhan keamanan, bukan nice-to-have.**

### `options JSON NULL` — ✅ TAMBAHKAN

Settings dengan tipe `select` atau `radio` membutuhkan daftar pilihan. Contoh: `appearance.theme` dengan options `[{"value":"light","label":"Terang"},{"value":"dark","label":"Gelap"}]`. Memungkinkan admin UI merender dropdown secara dinamis tanpa hardcode di frontend.

### `validation_rules JSON NULL` — ❌ TIDAK TAMBAHKAN (sekarang)

Ini menciptakan **dual validation system** yang sulit di-maintain: rules ada di DB dan juga di `SettingFormRequest`. Lebih baik validasi di-handle di `SettingService::set()` via PHP hardcode per-key. Dapat ditambahkan di masa depan jika dibutuhkan.

---

## 5. Review Tipe Data

### `village_profiles`

| Kolom | Tipe Lama | Tipe Final | Alasan Perubahan |
|---|---|---|---|
| `regency` | VARCHAR(100) | **VARCHAR(150)** | Nama kabupaten Indonesia bisa sangat panjang |
| `website` | VARCHAR(255) | **VARCHAR(500)** | URL bisa panjang dengan query parameters |
| `longitude` | DECIMAL(10,7) | **DECIMAL(11,7)** | **Bug fix**: ±180° butuh 4 digit sebelum koma |
| `hero_image` | VARCHAR(500) | **DIHAPUS** | Pindah ke tabel `banners` (M10) |
| `logo` | VARCHAR(500) | VARCHAR(500) | Tidak berubah (hanya rename ke `logo_path`) |
| Semua lainnya | — | Tidak berubah | Sudah tepat |

### `settings`

| Kolom Baru | Tipe | Catatan |
|---|---|---|
| `options` | JSON NULL | Untuk select/radio type |
| `sort_order` | SMALLINT UNSIGNED | Admin UI ordering |
| `is_encrypted` | BOOLEAN | Keamanan |
| `is_editable` | BOOLEAN | Lock system settings |

---

## 6. Storage Strategy

### Rekomendasi Struktur Direktori

```
storage/app/public/
│
├── village/
│   └── logo/                 # Logo resmi desa
│
├── banners/                  # Hero/slider images homepage (M10)
├── avatars/                  # Foto profil user admin
├── apparatus/                # Foto perangkat desa
│
├── content/
│   ├── articles/             # Featured image berita
│   └── announcements/        # Attachment pengumuman
│
├── gallery/                  # Foto galeri desa
├── videos/                   # Thumbnail video
│
├── potentials/
│   ├── umkm/
│   ├── tourism/
│   └── products/
│
└── documents/                # Dokumen publik (SK, peraturan, APBDes)
```

### Aturan File Naming

```
❌ BURUK: foto-kantor.jpg          (original filename — path traversal risk)
❌ BURUK: 1_logo.jpg               (ID-based — enumerable)
✅ BAIK:  01J5MXZP3KV9N7DQMB8.webp  (ULID + format tervalidasi)
```

Extension berasal dari **validated MIME type**, bukan nama file asli pengguna.

---

## 7. Caching Strategy

### Pendekatan: Tag-Based Cache (Redis Required)

```php
// Read dengan tag
Cache::tags(['village'])->rememberForever('village.profile', fn() => VillageProfile::first());
Cache::tags(['village', 'missions'])->rememberForever('village.missions', fn() => ...);
Cache::tags(['settings', 'seo'])->rememberForever('settings.seo', fn() => ...);

// Invalidasi granular
Cache::tags(['village'])->flush();    // Semua cache village
Cache::tags(['settings'])->flush();   // Semua cache settings
```

### Strategi Invalidasi per Event

| Event | Cache Di-invalidate | Dipanggil di |
|---|---|---|
| `VillageProfile` di-save | `Cache::tags(['village'])->flush()` | `VillageProfileService::save()` |
| `VillageMission` di-save/delete | `Cache::tags(['village', 'missions'])->flush()` | `VillageMissionService` |
| `Setting` di-save | `Cache::tags(['settings'])->flush()` | `SettingService::set()` |
| Logo di-upload | `Cache::tags(['village'])->flush()` | `VillageProfileService::uploadLogo()` |

**Fallback tanpa Redis**: `Cache::forget('specific.key')` yang di-trigger eksplisit dari Service Layer.

---

## 8. Service Layer

### `SettingService` — Wajib

Tanpa service, setiap bagian kode yang membaca setting harus tahu cara decrypt, cast tipe, dan invalidate cache. Dengan service:

```php
setting('seo.meta_title')                       // Helper function
app(SettingService::class)->get('key')          // Type-cast + decrypt otomatis
app(SettingService::class)->set('key', 'val')   // Encrypt + cache invalidation
```

### `VillageProfileService` — Wajib

Menangani: upload + resize logo (WebP conversion), cache invalidation, cleanup file lama saat logo di-update.

### Analisis SOLID

| Prinsip | Tanpa Service | Dengan Service |
|---|---|---|
| **S** Single Responsibility | ❌ Controller handle encryption + cache + upload | ✅ Service fokus satu domain |
| **O** Open/Closed | ❌ Edit controller untuk tambah fitur | ✅ Extend service tanpa sentuh controller |
| **D** Dependency Inversion | ❌ Dependen langsung ke Model | ✅ Dependen ke contract/interface |
| **Testability** | ❌ `Setting::where()` tidak bisa di-mock | ✅ Service bisa di-mock/stub |

---

## 9. Security Review

### 🔴 Sensitive Settings Exposure
Settings `is_public = false` tidak boleh pernah ter-expose via API publik. `SettingService::forPublic()` hanya query `WHERE is_public = true AND is_encrypted = false`.

### 🔴 Encrypted Settings Storage
Flag `is_encrypted` + `Crypt::encryptString()`/`decryptString()` di `SettingService`. Kunci dari `APP_KEY` — **jangan pernah commit ke Git**.

### 🟡 Path Traversal via File Upload
`logo_path` tidak pernah berasal dari user input langsung. Selalu di-generate via `Storage::putFile()` atau custom ULID naming. Validasi MIME type di server: hanya `image/jpeg`, `image/png`, `image/webp`, `image/svg+xml`.

### 🟡 XSS via Rich Content
`history`, `vision` di-render sebagai HTML. Di Vue: gunakan `v-html` HANYA dengan DOMPurify. Atau simpan Markdown dan convert di frontend.

### 🟢 SQL Injection — Aman (Eloquent/Query Builder)
### 🟢 IDOR — Tidak Berisiko (singleton + admin-only)

---

## 10. Scalability — Proyeksi 10 Tahun

| Fitur Masa Depan | Status |
|---|---|
| Layanan Surat Online | ✅ Tabel baru, M2 tidak terdampak |
| Pengaduan Masyarakat | ✅ Tabel baru, M2 tidak terdampak |
| API Mobile | ✅ `is_public` flag sudah mendukung |
| Integrasi OpenSID | ✅ `village_code` adalah kunci integrasi |
| Integrasi SSO Pemerintah | ✅ Concern M1, M2 tidak terdampak |
| Multi-bahasa | ⚠️ Perlu tabel terjemahan di masa depan |
| Multi-desa (SaaS) | ⚠️ Desain saat ini **single-tenant** — refactor besar jika berubah |

---

## Desain Final yang Direkomendasikan

### Tabel `village_profiles`

```
id                BIGINT UNSIGNED   PK AUTO_INCREMENT
village_name      VARCHAR(255)      NOT NULL
village_code      VARCHAR(20)       NULL           -- Kode BPS 10 digit
district          VARCHAR(100)      NOT NULL
regency           VARCHAR(150)      NOT NULL       -- Diperbesar dari 100
province          VARCHAR(100)      NOT NULL
postal_code       VARCHAR(10)       NULL
address           TEXT              NOT NULL
phone             VARCHAR(20)       NULL
email             VARCHAR(255)      NULL
website_url       VARCHAR(500)      NULL           -- Diubah dari 'website' VARCHAR(255)
history           LONGTEXT          NULL
vision            TEXT              NULL
area_hectares     DECIMAL(10,2)     NULL
latitude          DECIMAL(10,7)     NULL
longitude         DECIMAL(11,7)     NULL           -- BUG FIX: DECIMAL(10,7) → (11,7)
established_year  SMALLINT UNSIGNED NULL
logo_path         VARCHAR(500)      NULL           -- Diubah dari 'logo'
                                                   -- hero_image DIHAPUS → M10 banners
created_at        TIMESTAMP         NULL
updated_at        TIMESTAMP         NULL
```

---

### Tabel `village_missions` (BARU)

```
id                   BIGINT UNSIGNED    PK AUTO_INCREMENT
village_profile_id   BIGINT UNSIGNED    NOT NULL  FK → village_profiles.id
content              TEXT               NOT NULL
sort_order           SMALLINT UNSIGNED  NOT NULL DEFAULT 0
created_at           TIMESTAMP          NULL
updated_at           TIMESTAMP          NULL

FK:    village_profile_id → village_profiles.id  (CASCADE DELETE, CASCADE UPDATE)
INDEX: (village_profile_id, sort_order)
```

---

### Tabel `settings`

```
id            BIGINT UNSIGNED    PK AUTO_INCREMENT
key           VARCHAR(100)       NOT NULL UNIQUE
value         LONGTEXT           NULL
type          VARCHAR(20)        NOT NULL DEFAULT 'string'
group         VARCHAR(50)        NOT NULL
label         VARCHAR(255)       NOT NULL
description   TEXT               NULL
options       JSON               NULL               -- BARU
is_public     BOOLEAN            NOT NULL DEFAULT FALSE
is_editable   BOOLEAN            NOT NULL DEFAULT TRUE   -- BARU
is_encrypted  BOOLEAN            NOT NULL DEFAULT FALSE  -- BARU
sort_order    SMALLINT UNSIGNED  NOT NULL DEFAULT 0      -- BARU
created_at    TIMESTAMP          NULL
updated_at    TIMESTAMP          NULL

UNIQUE:    key
INDEX:     group
INDEX:     is_public
COMPOSITE: (group, sort_order)
COMPOSITE: (group, is_public)
```

---

## Ringkasan 11 Perubahan dari Desain Awal

| # | Perubahan | Tipe | Dampak |
|---|---|---|---|
| 1 | `mission JSON` → tabel `village_missions` | Structural | Admin UX lebih baik, reorder atomik |
| 2 | Hapus `hero_image` dari `village_profiles` | Removal | Duplikasi dengan M10 `banners` |
| 3 | `logo` → `logo_path` | Rename | Eksplisit path vs binary/URL |
| 4 | `website` → `website_url` | Rename | Eksplisit format URL |
| 5 | `longitude DECIMAL(10,7)` → `DECIMAL(11,7)` | **Bug Fix** | Koreksi presisi koordinat |
| 6 | `regency VARCHAR(100)` → `VARCHAR(150)` | Resize | Nama kabupaten panjang |
| 7 | `website_url VARCHAR(500)` (dari 255) | Resize | URL modern bisa panjang |
| 8 | Tambah `sort_order` di settings | New Column | Admin UI ordering |
| 9 | Tambah `is_editable` di settings | New Column | Lock system settings |
| 10 | Tambah `is_encrypted` di settings | New Column | Keamanan settings sensitif |
| 11 | Tambah `options JSON` di settings | New Column | Dynamic select/radio UI |

---

> **Status**: Menunggu persetujuan desain final sebelum migration ditulis.
