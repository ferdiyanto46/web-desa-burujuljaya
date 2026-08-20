# Security Guide

- **Authentication**: Laravel Fortify terstandarisasi via Session token dan Inertia CSRF token yang otomatis melekat di setiap request.
- **Authorization**: Diatur murni melalui Spatie Policy & Gates. Wajib dicek di awal Controller.
- **CSRF & XSS**: CSRF Token otomatis terkirim melalui axios/router Inertia. Vue merender konten DOM XSS-safe kecuali disengaja menggunakan v-html (jika ini dilakukan wajib Purifier HTML).
- **Validation**: Seluruh Input form harus disanitasi oleh FormRequests.
- **File Upload**: Seluruh gambar dimutasi file name-nya oleh Laravel via ->store() untuk menghindari remote execution scripts, dan folder storage dilimit MIME types-nya di validasi Request.
- **Mass Assignment**: Variabel dari FormRequest yang lolos akan dimuat secara fillable. Properti sensitif seperti is_admin dilindungi tidak berada dalam fillable tanpa validasi spesifik.
