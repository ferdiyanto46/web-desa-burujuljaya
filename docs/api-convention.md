# API Convention

- **Format Output**: Semua di bungkus menggunakan JsonResource. Tidak terekspos properti hidden database.
- **Naming Convention**: Properti resource wajib menggunakan format snake_case untuk konsistensi.
- **Date Format**: Format standar ISO-8601 ->format('c').
- **Pagination**: Gunakan bawaan Laravel ->paginate()->withQueryString(). Inertia otomatis meresolve link dan meta page.
- **Search & Filter**: Via parameter URL GET (?search=x&status=y). Backend menangkap parameter ini untuk menyaring di Query builder Model.
- **Validation Error**: Akan melempar respons 422 HTTP, ditangkap Inertia untuk di-render merah di frontend form otomatis.
- **Authorization Error**: Melempar HTTP 403 Forbidden. Inertia dapat di-setup untuk catch modal atau layout kustom.
