<?php

namespace App\Http\Requests;

use App\Enums\PublicationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTourismRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:tourism_categories,id'],
            'title' => ['required', 'string', 'max:255', 'unique:tourisms,title'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'max:5120'], // 5MB max

            // Location
            'address' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'string', 'max:255'],
            'longitude' => ['nullable', 'string', 'max:255'],
            'google_maps_url' => ['nullable', 'url', 'max:1000'],

            // Info
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'ticket_price' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],

            // Enterprise flags
            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'is_featured' => ['nullable', 'boolean'],
            'featured_order' => ['nullable', 'integer', 'min:0'],
            'is_pinned' => ['nullable', 'boolean'],

            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'og_image' => ['nullable', 'image', 'max:2048'],
            'canonical_url' => ['nullable', 'url', 'max:1000'],

            // Relations
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['exists:tourism_facilities,id'],

            // Gallery
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:5120'],
        ];
    }
}
