<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\PublicationStatus;
use App\Models\Article;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Article::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:article_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
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
            'category_id.required' => 'Kategori artikel wajib dipilih.',
            'category_id.exists' => 'Kategori artikel tidak valid.',
            'title.required' => 'Judul artikel wajib diisi.',
            'title.max' => 'Judul artikel maksimal 255 karakter.',
            'content.required' => 'Konten artikel wajib diisi.',
            'featured_image.image' => 'File gambar utama harus berupa berkas gambar.',
            'featured_image.mimes' => 'Format gambar harus berupa JPEG, PNG, JPG, WEBP, atau SVG.',
            'featured_image.max' => 'Ukuran gambar utama maksimal 10 MB.',
            'status.required' => 'Status artikel wajib ditentukan.',
        ];
    }
}
