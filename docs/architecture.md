# Architecture Documentation

## Backend Architecture
- **Thin Controller**: Controller hanya menerima Request, memvalidasi form (via FormRequest), mengecek otorisasi (Gate::authorize), memanggil Service, dan mereturn Response.
- **Fat Service**: Business logic diisolasi di Service Layer (app/Services). Menggunakan DB::transaction untuk C/U/D.
- **Resource Layer**: Semua objek Eloquent dibungkus menggunakan JsonResource untuk mencegah tereksposnya *hidden attributes*.
- **Policy**: Otorisasi dipusatkan pada Policy.
- **Observer**: Hanya dipakai untuk tugas sekunder pasif (seperti log aktivitas). Dilarang untuk penghapusan file di Observer.
- **MediaService**: Service tunggal lintas domain untuk unggah, ganti, dan hapus media.

## Frontend Architecture
- **Vue 3 Composition API**: Sepenuhnya menggunakan script setup lang="ts". Dilarang memakai Options API.
- **Strict TypeScript**: Bebas dari tipe any. Data payload direpresentasikan melalui Record<string, unknown> atau interface khusus.
- **Atomic Component**: Komponen shadcn berada di ui/, form generik di forms/.
- **SSR Flow**: Menggunakan vite build --ssr. Browser API hanya dieksekusi di dalam onMounted.

## Request Flow
Client -> Middleware (Auth/Role) -> Controller -> FormRequest -> Gate/Policy -> Service -> Database -> Controller -> API Resource -> Inertia -> Vue
## Authentication Flow
User login via endpoint Fortify, session dibentuk, diarahkan ke Dashboard. Inertia Middleware membagikan props `auth.user` ke frontend.
## Authorization Flow
Pengecekan di frontend dilakukan via properti `permissions` di dalam JSON payload. Di sisi backend, diblokir menggunakan Gate::authorize() di lapis Controller.