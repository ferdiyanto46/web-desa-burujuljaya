# 🗃️ Database Design — Website Resmi Desa Burujuljaya

> **Normalisasi**: 3NF | **Engine**: InnoDB | **Charset**: utf8mb4 | **Collation**: utf8mb4_unicode_ci

---

## 1. Prinsip Desain

| Prinsip | Implementasi |
|---|---|
| **Surrogate PK** | `id BIGINT UNSIGNED AUTO_INCREMENT` di semua tabel |
| **UUID** | Kolom `uuid CHAR(36)` terpisah di tabel konten utama |
| **Soft Delete** | `deleted_at` hanya pada tabel konten (tidak pada lookup/log) |
| **Timestamps** | `created_at`, `updated_at` di semua tabel |
| **Audit Log** | `activity_logs` terpusat, immutable (tanpa `updated_at`) |
| **Indeks** | Pada semua FK, kolom yang sering di-search/filter/sort |
| **FK Constraint** | `RESTRICT` untuk referensi kritis, `SET NULL` untuk opsional |
| **Enum** | Untuk status terbatas dan mudah di-extend via migration |
| **JSON** | Untuk data semi-structured yang tidak perlu di-query per field |
| **Charset** | `utf8mb4` untuk mendukung emoji dan karakter Unicode penuh |

---

## 2. Analisis Normalisasi

### 1NF (First Normal Form) ✅
- Setiap tabel memiliki Primary Key
- Semua kolom memiliki nilai atomik
- Tidak ada repeating groups
- `mission` di `village_profiles` menggunakan JSON, bukan kolom terulang

### 2NF (Second Normal Form) ✅
- Semua tabel menggunakan surrogate PK (single column)
- Tidak ada partial dependency karena tidak ada composite PK

### 3NF (Third Normal Form) ✅ — Transitive Dependencies yang Dieliminasi

```
PELANGGARAN (sebelum):
  articles: user_id, user_name, user_email
  ↑ user_name bergantung pada user_id, bukan pada articles.id

SOLUSI (sesudah):
  articles: user_id FK → users.id
  users: id, name, email   ← satu sumber kebenaran

ElimInasi lain:
  apparatus    → position_id FK (bukan position_name langsung)
  umkm         → category_id FK (bukan category_name langsung)
  documents    → category_id FK (bukan category_name langsung)
  articles     → category_id FK (bukan category_name langsung)
```

### Denormalisasi Terkontrol (Deliberate)

| Field | Tabel | Alasan |
|---|---|---|
| `views` | `articles` | Counter sering di-increment; join terpisah mahal |
| `download_count` | `documents` | Sama seperti views |
| `file_extension` | `documents` | Derived dari file_path tapi disimpan untuk query cepat |
| `cover_photo` | `gallery_albums` | Menghindari subquery setiap load list album |

---

## 3. ERD — Relasi Antar Entitas

```
users ──1:N──► articles ──N:1──► article_categories
  │  ──1:N──► announcements
  │  ──1:N──► agendas
  │  ──1:N──► gallery_albums ──1:N──► gallery_photos
  │  ──1:N──► videos
  │  ──1:N──► umkm ──N:1──► umkm_categories
  │  ──1:N──► tourisms ──N:1──► tourism_categories
  │  ──1:N──► products ──N:1──► product_categories
  │                  └──N:1──► umkm (optional)
  │  ──1:N──► documents ──N:1──► document_categories
  └──1:N──► activity_logs (nullable)
  └──1:N──► contact_messages.replied_by (nullable)

apparatus_positions ──1:N──► apparatus

statistic_categories ──1:N──► village_statistics

village_profiles  (1 row — singleton)
settings          (key-value store)
banners           (standalone)
faqs              (standalone)
contact_messages  (standalone, replied_by → users nullable)

── Spatie RBAC ────────────────────────────────────────────────
users ──pivot── roles ──pivot── permissions
(via model_has_roles, model_has_permissions, role_has_permissions)
```

---

## 4. Daftar 31 Tabel

| # | Tabel | Soft Del | UUID | Keterangan |
|---|---|---|---|---|
| 1 | `users` | ✅ | ✅ | Enhanced dari starter kit |
| 2 | `roles` | ❌ | ❌ | Spatie auto-generated |
| 3 | `permissions` | ❌ | ❌ | Spatie auto-generated |
| 4 | `model_has_roles` | ❌ | ❌ | Spatie pivot |
| 5 | `model_has_permissions` | ❌ | ❌ | Spatie pivot |
| 6 | `role_has_permissions` | ❌ | ❌ | Spatie pivot |
| 7 | `village_profiles` | ❌ | ❌ | Singleton |
| 8 | `settings` | ❌ | ❌ | Key-value store |
| 9 | `article_categories` | ✅ | ❌ | Lookup dengan soft delete |
| 10 | `articles` | ✅ | ✅ | FULLTEXT index |
| 11 | `announcements` | ✅ | ✅ | |
| 12 | `agendas` | ✅ | ✅ | |
| 13 | `apparatus_positions` | ❌ | ❌ | Lookup |
| 14 | `apparatus` | ✅ | ✅ | |
| 15 | `gallery_albums` | ✅ | ✅ | |
| 16 | `gallery_photos` | ❌ | ❌ | Hard delete + file cleanup event |
| 17 | `videos` | ✅ | ✅ | |
| 18 | `umkm_categories` | ❌ | ❌ | Lookup |
| 19 | `umkm` | ✅ | ✅ | |
| 20 | `tourism_categories` | ❌ | ❌ | Lookup |
| 21 | `tourisms` | ✅ | ✅ | |
| 22 | `product_categories` | ❌ | ❌ | Lookup |
| 23 | `products` | ✅ | ✅ | |
| 24 | `statistic_categories` | ❌ | ❌ | Lookup |
| 25 | `village_statistics` | ❌ | ❌ | |
| 26 | `document_categories` | ❌ | ❌ | Lookup |
| 27 | `documents` | ✅ | ✅ | |
| 28 | `banners` | ✅ | ❌ | |
| 29 | `faqs` | ❌ | ❌ | |
| 30 | `contact_messages` | ❌ | ❌ | Status-based archiving |
| 31 | `activity_logs` | ❌ | ❌ | Immutable, append-only |

---

## 5. Spesifikasi Kolom Kunci

### `users`

| Kolom | Tipe | Default | Index | Keterangan |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | AUTO_INC | PK | |
| uuid | CHAR(36) | — | UNIQUE | Referensi eksternal |
| name | VARCHAR(255) | — | — | |
| email | VARCHAR(255) | — | UNIQUE | |
| password | VARCHAR(255) | — | — | Bcrypt |
| avatar | VARCHAR(500) | NULL | — | Path storage |
| phone | VARCHAR(20) | NULL | — | |
| is_active | TINYINT(1) | 1 | IDX | Filter user aktif |
| email_verified_at | TIMESTAMP | NULL | — | |
| login_attempts | TINYINT UNSIGNED | 0 | — | Lockout counter |
| locked_until | TIMESTAMP | NULL | IDX | NULL = tidak terkunci |
| remember_token | VARCHAR(100) | NULL | — | |
| created_at, updated_at | TIMESTAMP | NULL | — | |
| deleted_at | TIMESTAMP | NULL | IDX | Soft delete |

### `articles`

| Kolom | Tipe | Default | Index | Keterangan |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | AUTO_INC | PK | |
| uuid | CHAR(36) | — | UNIQUE | |
| user_id | BIGINT UNSIGNED | — | FK+IDX | ON DELETE RESTRICT |
| category_id | BIGINT UNSIGNED | — | FK+IDX | ON DELETE RESTRICT |
| title | VARCHAR(300) | — | — | |
| slug | VARCHAR(320) | — | UNIQUE | |
| excerpt | VARCHAR(500) | NULL | — | |
| content | LONGTEXT | — | FULLTEXT | title+content+excerpt |
| featured_image | VARCHAR(500) | NULL | — | |
| status | ENUM | 'draft' | IDX | draft/published/archived |
| is_featured | TINYINT(1) | 0 | IDX | |
| views | BIGINT UNSIGNED | 0 | — | |
| published_at | TIMESTAMP | NULL | IDX | |
| meta_title | VARCHAR(70) | NULL | — | SEO |
| meta_description | VARCHAR(160) | NULL | — | SEO |
| og_image | VARCHAR(500) | NULL | — | OG Tag |
| created_at, updated_at | TIMESTAMP | NULL | — | |
| deleted_at | TIMESTAMP | NULL | IDX | |

**Composite Index**: `(status, published_at, id)` → paginasi publik utama

### `activity_logs`

| Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK |
| user_id | BIGINT UNSIGNED | YES | FK users, SET NULL on delete |
| action | VARCHAR(50) | NO | created/updated/deleted/login/… |
| model_type | VARCHAR(255) | YES | Morph: App\\Models\\Article |
| model_id | BIGINT UNSIGNED | YES | Morph: id model |
| description | VARCHAR(500) | NO | Human-readable |
| properties | JSON | YES | {old:{…}, new:{…}} |
| ip_address | VARCHAR(45) | NO | IPv4 atau IPv6 |
| user_agent | VARCHAR(500) | YES | |
| created_at | TIMESTAMP | YES | **Tidak ada updated_at** |

---

## 6. Composite Indexes Kritis

| Tabel | Kolom | Tujuan |
|---|---|---|
| `articles` | `(status, published_at, id)` | Paginasi berita terbaru |
| `articles` | `(is_featured, status)` | Berita unggulan |
| `articles` | `(category_id, status)` | Filter per kategori |
| `announcements` | `(status, is_pinned, end_date)` | Pengumuman aktif |
| `agendas` | `(status, start_datetime)` | Agenda mendatang |
| `apparatus` | `(is_active, order)` | Tampilan aparatur publik |
| `gallery_photos` | `(album_id, order)` | Foto per album |
| `activity_logs` | `(model_type, model_id)` | Log per entitas |
| `activity_logs` | `(user_id, created_at)` | Log per user |
| `village_statistics` | `(category_id, year)` | Statistik per tahun |
| `documents` | `(is_public, category_id)` | Dokumen publik per kategori |

---

## 7. Strategi Soft Delete

**Menggunakan `deleted_at`** (konten yang bisa di-restore):
`users`, `article_categories`, `articles`, `announcements`, `agendas`,
`apparatus`, `gallery_albums`, `videos`, `umkm`, `tourisms`, `products`,
`documents`, `banners`

**Tidak menggunakan soft delete** dan alasannya:

| Tabel | Strategi Pengganti |
|---|---|
| `activity_logs` | Immutable — tidak pernah dihapus via UI |
| `gallery_photos` | Hard delete + Storage::delete() via model event |
| `contact_messages` | `status` field: unread → read → replied → archived |
| `apparatus_positions` | FK RESTRICT melindungi dari penghapusan tidak sengaja |
| Semua lookup/kategori | FK RESTRICT + validasi di service sebelum delete |

---

## 8. Strategi Audit & Activity Log

### Events yang Dicatat

| Event | action | model |
|---|---|---|
| Login berhasil | `login` | User |
| Login gagal | `login_failed` | — |
| Logout | `logout` | User |
| Create data | `created` | Model terkait |
| Update data | `updated` | Model terkait |
| Delete (soft) | `deleted` | Model terkait |
| Restore data | `restored` | Model terkait |
| Publish artikel | `published` | Article |
| Ubah role user | `role_changed` | User |
| Ubah pengaturan | `setting_changed` | — |
| Backup database | `backup_created` | — |

### Properties Snapshot
```json
{
  "old": { "status": "draft", "title": "Judul Lama" },
  "new": { "status": "published", "title": "Judul Baru" }
}
```

### Implementasi
- Log ditulis via `ActivityLogService` di **Service Layer** (bukan observer — lebih eksplisit)
- `user_id` nullable untuk event sistem/cron job
- Log **tidak bisa dihapus** via UI (hanya via Artisan command untuk rotasi)
- Pertimbangkan archiving log > 1 tahun ke `activity_logs_archive`

---

## 9. Rencana Migration per Modul

| Modul | Tabel | Status |
|---|---|---|
| **M1 — Users** | Alter `users`: +uuid, +phone, +is_active, +login_attempts, +locked_until, +deleted_at | 🔜 |
| **M2 — Foundation** | `village_profiles`, `settings` | ⏳ |
| **M3 — Articles** | `article_categories`, `articles` | ⏳ |
| **M4 — Announcements & Agenda** | `announcements`, `agendas` | ⏳ |
| **M5 — Apparatus** | `apparatus_positions`, `apparatus` | ⏳ |
| **M6 — Gallery & Video** | `gallery_albums`, `gallery_photos`, `videos` | ⏳ |
| **M7 — Potentials** | `umkm_categories`, `umkm`, `tourism_categories`, `tourisms`, `product_categories`, `products` | ⏳ |
| **M8 — Statistics** | `statistic_categories`, `village_statistics` | ⏳ |
| **M9 — Documents** | `document_categories`, `documents` | ⏳ |
| **M10 — Website Mgmt** | `banners`, `faqs`, `contact_messages` | ⏳ |
| **M11 — Audit** | `activity_logs` | ⏳ |

> *Spatie tables (`roles`, `permissions`, pivot) dibuat via `php artisan vendor:publish` + migrate, bukan migration custom.*
