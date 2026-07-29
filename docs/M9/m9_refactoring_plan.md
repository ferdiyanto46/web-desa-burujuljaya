# 🛠️ Refactoring Plan — Module 9 (Documents)

## 1. Objective
Merujuk pada temuan *Architecture Review* di dalam *Verification Report Module 9*, draf *refactoring* ini ditujukan eksklusif untuk mengeksekusi penambalan celah krusial terkait inkonsistensi Transaksi Pangkalan Data vs Operasi I/O Fisik (Disk Storage). Perbaikan akan berpegang pada pilar *Laravel Best Practice* & *Clean Architecture*, tanpa merombak apalagi mengubah spesifikasi bisnis Modul 9 (struktur kolom dan pola operasi tetap bertahan murni).

## 2. Refactoring Scope (The "What" and "Why")

### Finding 1: Database Transaction Sync Failure [Critical]
- **Kondisi Saat Ini**: Metode `updated()` dan `forceDeleted()` di dalam `DocumentObserver` secara instan mengeksekusi `Storage::disk('public')->delete()`.
- **Dampak Arsitektural**: Bertentangan dengan hukum *ACID Properties (Atomicity)*. Apabila kelak *Service Layer* membungkus operasi penghapusan menggunakan blok kode `DB::transaction()` dan pada saat proses tersebut bergulir mendadak timbul kesalahan *(error)*, Database akan membatalkan perubahan *(DB Rollback)*. Akan tetapi, berkas fisik PDF yang telah kadung dihapus oleh kode `Storage::delete` milik *Observer* tidak mungkin dipulihkan. Hasilnya adalah *"Ghost Record"* (data masih tercatat di pangkalan data namun berkasnya raib di diska).
- **Strategi Refactoring**: 
  - Mengimplementasikan pilar *Laravel Best Practice* dengan memanfaatkan antarmuka properti khusus Observer bawaan Laravel 8.x/9.x/10.x ke atas: menambahkan deklarasi properti `public bool $afterCommit = true;` pada level teratas kelas `DocumentObserver`.
  - Pemasangan bendera ini akan menunda eksekusi _event handler_ (`updated` maupun `forceDeleted`) hingga *Query Builder* menyatakan kesuksesan tunggal via perintah final `DB::commit()`. Berkas tidak akan pernah terhapus sebelum *row* tabel dinyatakan aman.

## 3. Dependency Check
- Operasi refactoring ini murni menambal arsitektur internal *Observer*, sehingga dijamin bebas bentrok *(Zero Impact)* dengan Modul 1 hingga Modul 8, maupun fungsi inti Model `Document` itu sendiri.

## 4. Execution Order

**Langkah Tunggal: Modifikasi Observer**
1. Buka berkas `app/Observers/DocumentObserver.php`.
2. Sisipkan deklarasi `public bool $afterCommit = true;` di bagian paling atas kerangka kelas.
3. Jalankan `vendor/bin/pint --format agent` untuk menjaga standardisasi PSR.

## 5. Acceptance Checklist
- [ ] Berkas fisik dijamin terlindungi dari kegagalan transaksi *Database Rollback*.
- [ ] Properti `public bool $afterCommit = true;` terverifikasi bersarang di dalam `DocumentObserver.php`.
- [ ] Tidak ada spesifikasi teknis dan bisnis Modul 9 yang melenceng.
- [ ] `Laravel Pint` kembali mencetak status *Exit Code 0*.
