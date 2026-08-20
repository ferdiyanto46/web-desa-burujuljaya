# Module Implementation Checklist

Ikuti checklist ini untuk menjamin konsistensi saat membuat fitur baru:

1. [ ] Buat Migration, Model (termasuk Casts, Fillable), Factory, Seeder.
2. [ ] Buat Form Requests (Store & Update).
3. [ ] Buat Resource Class.
4. [ ] Buat Policy berdasarkan relasi Spatie.
5. [ ] Buat Service Class (C/U/D).
6. [ ] Buat Controller (Index, Create, Edit, dll).
7. [ ] Tambahkan route di routes/admin.php.
8. [ ] Tambahkan interface TypeScript di resources/js/types.
9. [ ] Buat file service TS resources/js/services/namamodul.ts.
10. [ ] Buat View Vue: Index.vue (DataTable), Create.vue, Edit.vue.
11. [ ] Buat pest test: CRUD, Upload, Policy.
