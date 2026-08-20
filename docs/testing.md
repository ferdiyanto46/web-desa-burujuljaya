# Testing Guide

Engine uji utama adalah Pest PHP. Selalu jalankan uji via CLI:
`php artisan test --compact`

## Jenis Pengujian
1. **Unit Test**: Untuk Service spesifik (bila algoritmik terpisah).
2. **Feature Test**: CRUD utama, simulasi FormRequest, DB assertion.
3. **Authorization Test**: Uji akses user tanpa privilege yang memadai (expect 403).
4. **Validation Test**: Uji input kosong, string batas atas (expect SessionHasErrors).
5. **Upload Test**: Memakai Storage::fake('public'), tes storage upload & missing assertion untuk image replacement.
6. **SSR Test**: Jalankan npm run build --ssr dan amati terminal jika terdapat mismatch browser element rendering.
