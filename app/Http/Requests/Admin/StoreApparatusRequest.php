<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreApparatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin-desa', 'super-admin'])
            || ($this->user()?->can('create apparatus') ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'position_id' => ['required', 'exists:apparatus_positions,id'],
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'education' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
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
            'position_id.required' => 'Jabatan perangkat desa wajib dipilih.',
            'position_id.exists' => 'Jabatan yang dipilih tidak valid.',
            'name.required' => 'Nama lengkap perangkat desa wajib diisi.',
            'photo.image' => 'Berkas foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPEG, PNG, JPG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal adalah 5MB.',
        ];
    }
}
