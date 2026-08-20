# Frontend Documentation

## Components
- Terpecah menjadi ui/ (primitif shadcn), forms/, dan tables/. Komponen ini bebas dependensi logika database, bersifat presentasional dan atomic.
## Pages
- Merupakan wrapper terluar dari route Inertia. Mengonsumsi komponen, memanggil usePage(), merender layouts, dan menyajikan metadata HEAD.
## Types
- Memuat definisi global.d.ts dan struktur objek Model API dari backend untuk keperluan autocomplete TypeScript.
## Stores/Services
- Tidak ada store state management yang kompleks. Aplikasi mengandalkan inertia props dari backend. `services/` merupakan wrapper untuk call Inertia router method post/put untuk mempermudah form submit di halaman spesifik.
