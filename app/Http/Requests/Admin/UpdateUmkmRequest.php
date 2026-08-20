<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\PublicationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:umkm_categories,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:2048'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'operating_hours' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'is_featured' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'featured_order' => ['nullable', 'integer'],
        ];
    }
}
