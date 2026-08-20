# Backend Documentation

## Controllers
- Terletak di app/Http/Controllers/Admin. Bertanggung jawab terhadap aliran HTTP murni, mengikat Gate::authorize() lalu mengirim respon Inertia atau RedirectResponse.
## Services
- Terletak di app/Services. Membungkus segala jenis mutasi basis data (create, update, delete) di dalam DB::transaction().
## Resources
- Terletak di app/Http/Resources. Membersihkan field Eloquent sebelum dibuang ke klien. Melindungi URL relasi dan field timestamp.
## Requests & Policies
- Requests ditaruh di app/Http/Requests. Policies di app/Policies/.
