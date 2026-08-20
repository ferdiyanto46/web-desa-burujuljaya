<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\PublicationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update documents') ?? false;
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
            'category_id' => ['required', 'exists:document_categories,id'],
            'status' => ['required', Rule::enum(PublicationStatus::class)],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['required', 'boolean'],
            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
                'max:10240', // 10MB
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
