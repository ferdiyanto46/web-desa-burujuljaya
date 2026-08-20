# Coding Standard

## Backend
- **Thin Controller**: Controller tidak boleh memanipulasi model secara langsung. Logika diserahkan ke Service.
- **Fat Service**: Tempatkan di app/Services/. Jangan panggil return view/response dari Service.
- **Security**: Selalu gunakan Gate::authorize() dan FormRequest. Jangan gunakan request()->all().
- **Upload**: Gunakan injeksi MediaService. Tidak ada logika disk upload manual yang tercecer.
- **Resource**: Semua output harus via Resource class.

## Frontend
- **Composition API Only**: Gunakan script setup. Dilarang menggunakan Options API.
- **TypeScript Strict**: Dilarang menggunakan tipe 'any'. Wajib gunakan 'unknown' atau interface yang spesifik, atau Record<string, unknown>.
- **No Axios**: Wajib menggunakan Ineria router/useForm.
- **SSR Safe**: Dilarang langsung mengakses window di luar context hook vue (onMounted).
