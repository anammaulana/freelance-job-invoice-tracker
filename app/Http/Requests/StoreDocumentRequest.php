<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('documents.manage') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'document' => [
                'required',
                File::types(Document::ALLOWED_MIMES)
                    ->max(Document::MAX_UPLOAD_KILOBYTES),
            ],
        ];
    }
}
