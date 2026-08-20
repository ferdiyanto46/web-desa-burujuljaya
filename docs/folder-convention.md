# Folder Convention

## Backend (app/)
- **Http/Controllers**: Layer presentasi HTTP.
- **Http/Requests**: Validasi input spesifik.
- **Http/Resources**: Transformasi tipe dan filter payload JSON.
- **Services**: Inti logika (Domain Logic).
- **Policies**: Penjaga pintu otorisasi peran.
- **Models**: Pemetaan entitas basis data (hanya relasi, cast, scope).
- **Observers**: Khusus trigger event logging, bukan bussines logic utama.
- **Enums**: Enumerasi status statis.

## Frontend (resources/js/)
- **components/ui**: Komponen radikal bawaan shadcn-vue. Jangan taruh logika bisnis.
- **components/forms**: Input, Select generik milik aplikasi kita.
- **components/tables**: Wrapper DataTable generik.
- **pages**: Komponen level halaman, mewakili sebuah URL/Route.
- **services**: Fungsi pembungkus router.post Inertia.
- **types**: Kumpulan tipe dan antarmuka TypeScript statik.
- **layouts**: Wrapper antarmuka layout (Admin/Public).

## Root & Config
- **routes/**: Definisi route (web.php, admin.php).
- **config/**: Konfigurasi env statis.
- **database/**: Migrations, factories, dan seeders.
- **storage/**: Lokasi file sistem, storage/app/public wajib di-symlink.
- **public/**: Build vite dan asset statis publik.
