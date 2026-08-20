# Deployment Guide

Proyek Laravel+Inertia (SSR) ini siap digunakan di Production.

## Shared Hosting (Limited Shell)
- Gunakan konfigurasi QUEUE_CONNECTION=sync di .env.
- SSR Node.js mungkin tidak disokong, cukup gunakan `npm run build` tanpa --ssr.
- Pastikan folder public di-symlink secara manual jika dibutuhkan.

## VPS / Ploi / Forge
- Web Server: Nginx (Point ke /public).
- Queue: Setup Supervisor (php artisan queue:work).
- Scheduler: Setup Cron (* * * * * php artisan schedule:run).
- SSR: Install Node.js dan setup PM2 (`pm2 start bootstrap/ssr/ssr.js`).
- Eksekusi Optimasi:
  ```
  composer install --no-dev -o
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```
