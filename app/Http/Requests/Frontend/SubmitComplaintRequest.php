<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class SubmitComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any public user can submit a complaint
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isAnonymous = $this->boolean('is_anonymous');

        return [
            'category_id' => ['required', 'exists:complaint_categories,id'],
            'reporter_name' => [$isAnonymous ? 'nullable' : 'required', 'string', 'max:255'],
            'reporter_email' => ['nullable', 'email', 'max:255'],
            'reporter_phone' => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_anonymous' => ['nullable', 'boolean'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'mimes:jpeg,png,jpg,pdf,doc,docx', 'max:10240'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'attachments.max' => 'Maksimal lampiran yang dapat diunggah adalah 3 file.',
            'attachments.*.max' => 'Ukuran file lampiran tidak boleh lebih dari 10 MB.',
            'attachments.*.mimes' => 'Format file lampiran harus berupa JPG, JPEG, PNG, PDF, DOC, atau DOCX.',
            'attachments.*.file' => 'File lampiran tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'Kategori Masalah',
            'reporter_name' => 'Nama Lengkap',
            'reporter_phone' => 'Nomor Telepon/WA',
            'reporter_email' => 'Email',
            'title' => 'Judul Laporan',
            'body' => 'Deskripsi Laporan',
            'location' => 'Lokasi Kejadian',
            'attachments' => 'Lampiran',
            'attachments.0' => 'Lampiran ke-1',
            'attachments.1' => 'Lampiran ke-2',
            'attachments.2' => 'Lampiran ke-3',
        ];
    }
}
