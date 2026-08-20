<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateVillageProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin-desa', 'super-admin'])
            || ($this->user()?->can('village-profile.update') || $this->user()?->can('manage village profile'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'village_name' => ['required', 'string', 'max:255'],
            'village_code' => ['nullable', 'string', 'max:50'],
            'district' => ['required', 'string', 'max:255'],
            'regency' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website_url' => ['nullable', 'string', 'max:255'],
            'history' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'area_hectares' => ['nullable', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'established_year' => ['nullable', 'integer', 'min:1000', 'max:'.(int) date('Y')],
            'logo' => ['nullable', 'file', 'mimes:jpeg,png,jpg,svg,webp', 'max:5120'],
            'missions' => ['nullable', 'array'],
            'missions.*' => ['nullable'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'village_name.required' => 'Nama desa wajib diisi.',
            'district.required' => 'Kecamatan wajib diisi.',
            'regency.required' => 'Kabupaten/Kota wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'address.required' => 'Alamat kantor desa wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'logo.file' => 'Berkas logo harus berupa file yang valid.',
            'logo.mimes' => 'Format logo harus berupa file gambar (PNG, JPG, JPEG, SVG, WEBP).',
            'logo.max' => 'Ukuran file logo maksimal adalah 5MB (5120 KB).',
            'established_year.integer' => 'Tahun berdiri harus berupa angka tahun.',
            'area_hectares.numeric' => 'Luas wilayah harus berupa angka.',
        ];
    }

    /**
     * Custom attribute names in Indonesian.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'village_name' => 'Nama Desa',
            'village_code' => 'Kode Desa',
            'district' => 'Kecamatan',
            'regency' => 'Kabupaten/Kota',
            'province' => 'Provinsi',
            'postal_code' => 'Kode Pos',
            'address' => 'Alamat Lengkap',
            'phone' => 'Nomor Telepon',
            'email' => 'Email Resmi',
            'website_url' => 'Website Resmi',
            'history' => 'Sejarah Desa',
            'vision' => 'Visi Desa',
            'area_hectares' => 'Luas Wilayah',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'established_year' => 'Tahun Berdiri',
            'logo' => 'Logo Desa',
        ];
    }
}
