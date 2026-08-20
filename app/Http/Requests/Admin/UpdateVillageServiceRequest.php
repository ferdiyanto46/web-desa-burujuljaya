<?php

namespace App\Http\Requests\Admin;

use App\Enums\PublicationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageServiceRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],

            'requirements' => ['nullable', 'string'],
            'procedures' => ['nullable', 'string'],

            'processing_time' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'string', 'max:255'],
            'service_hours' => ['nullable', 'string', 'max:255'],

            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],

            'downloadable_form' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],

            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
