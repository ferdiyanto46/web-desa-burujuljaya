# CRUD Blueprint Standardization

Panduan absolut saat membuat modul baru:

1. **Migration**: ID (integer), ULID (string), timestamps(), softDeletes().
2. **Model**: Traits HasUlid, SoftDeletes. Properti eksplisit $fillable. Scope scopeWithDetails().
3. **Factory & Seeder**: Sesuaikan untuk testing.
4. **Policy**: Implementasi viewAny, view, create, update, delete, restore, forceDelete.
5. **FormRequest**: Bikin StoreRequest (gambar required jika ada) dan UpdateRequest (nullable).
6. **Resource**: JSON rapi, amankan links dengan Route::has(). Format tanggal ->format('c').
7. **Service**: Panggil DB::transaction(). Injeksi MediaService jika butuh unggahan.
8. **Controller**: Ramping. Gate::authorize() di baris pertama tiap method.
9. **Vue Types**: Tambahkan interface tanpa any.
10. **Vue Service**: Buat router helper di resources/js/services/namamodul.ts.
11. **Vue Pages**: Index.vue (panggil DataTable), Create.vue, Edit.vue, Show.vue.
12. **Tests**: Pest Feature Test minimal untuk Authorization dan CRUD fungsional (termasuk Storage::fake).
