# Sistem Informasi Desa (Web Desa Burujuljaya)
Enterprise-grade village information system built with Laravel 13, Vue 3, Inertia.js v3, and TailwindCSS v4.

## Project Overview
Sistem Informasi Desa yang dirancang dengan arsitektur modern (Thin Controller, Fat Service) untuk menjamin skalabilitas, maintainability, dan Developer Experience (DX).

## Features
- **Public Portal**: Berita, Agenda, Pengumuman, Profil Desa, Transparansi.
- **Admin Dashboard**: Manajemen konten, pengelolaan penduduk, surat menyurat, pengaduan.
- **Role-Based Access Control**: Manajemen peran dinamis (Super Admin, Editor, Operator).

## Architecture Overview
Aplikasi menggunakan pola **Service-Oriented MVC**:
- **Backend**: Laravel 13 melayani request HTTP melalui Controller tipis, mendelegasikan logika ke Service Layer, dan mengembalikan data menggunakan API Resource.
- **Frontend**: SPA dengan Vue 3 (Composition API strict) dibalut Inertia.js v3. Desain atomic dengan komponen `shadcn-vue`.
- **Database**: ULID terintegrasi dengan ID numerik konvensional. Pendekatan Soft Deletes.

## Tech Stack
- PHP 8.3 & Laravel 13
- Vue 3 (Strict TypeScript) & Inertia.js v3
- TailwindCSS v4 & Radix Vue
- Pest PHP (Testing)

## Requirements
- PHP >= 8.3
- Node.js >= 20.x
- Composer 2.x
- MySQL 8.x / MariaDB 10.x / PostgreSQL 15.x

## Installation & Environment Configuration
1. Clone repositori: `git clone ...`
2. Copy `.env.example` ke `.env` dan sesuaikan kredensial basis data.
3. Install dependensi:
   ```bash
   composer install
   npm install
   ```
4. Generate key & Migrasi:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

## Local Development
Jalankan dua terminal secara bersamaan:
```bash
php artisan serve
npm run dev
```

## SSR & Build Production
```bash
npm run build
npm run build --ssr
# Untuk menjalankan server SSR (Opsional jika ingin dirender Node.js)
node bootstrap/ssr/ssr.js
```

## Storage, Queue, Scheduler
- **Storage**: Default menggunakan `public` disk (`storage/app/public`).
- **Queue**: Dikonfigurasi `database`. Jalankan `php artisan queue:work` di production.
- **Scheduler**: Tambahkan cron entry `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`.

## Folder Structure
- `app/Services`: Pusat logika bisnis (C/U/D).
- `app/Http/Resources`: Standar output JSON.
- `app/Policies`: Gerbang otorisasi.
- `resources/js/components/ui`: Komponen dasar UI.
- `resources/js/services`: Router pemanggil endpoint.

## Testing
```bash
php artisan test --compact
```

## Deployment
Lihat panduan lengkap di [docs/deployment.md](docs/deployment.md).

## Troubleshooting
- **Error Vite Manifest**: Jalankan `npm run build` atau `npm run dev`.
- **File Not Found**: Pastikan `php artisan storage:link` sudah dijalankan.

## Contribution Guide
Perhatikan panduan kontribusi di repositori sebelum melakukan *Pull Request*.

## License
MIT License.