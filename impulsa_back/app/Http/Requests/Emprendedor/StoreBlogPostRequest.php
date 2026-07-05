<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:220'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:180'],
            'bibliography' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:120'],
            'subcategory' => ['nullable', 'string', 'max:120'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'description_html' => ['required', 'string'],
            'publication_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive,draft'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'cover_image_file' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
            'attachment_file' => ['nullable', 'file', 'max:8192', 'mimes:pdf,doc,docx,txt'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'remove_attachment' => ['nullable', 'boolean'],
        ];
    }
}
