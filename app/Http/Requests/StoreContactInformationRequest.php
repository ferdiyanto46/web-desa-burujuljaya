<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PublicationStatus;
use App\Models\ContactInformation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', ContactInformation::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],

            'address' => ['nullable', 'string'],
            'google_maps_embed' => ['nullable', 'string'],
            'google_maps_url' => ['nullable', 'url', 'max:255'],

            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],

            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],

            'office_hours' => ['nullable', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],

            'featured_image' => ['nullable', 'image', 'max:2048'],

            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['boolean'],
            'sort_order' => ['integer'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:255'],
        ];
    }
}
