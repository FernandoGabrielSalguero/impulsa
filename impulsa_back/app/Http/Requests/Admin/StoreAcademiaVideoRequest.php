<?php

namespace App\Http\Requests\Admin;

use App\Support\AcademiaLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAcademiaVideoRequest extends FormRequest
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
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:180'],
            'author_instagram' => ['nullable', 'string', 'max:255', 'url'],
            'author_linkedin' => ['nullable', 'string', 'max:255', 'url'],
            'category' => ['nullable', 'string', 'max:120'],
            'subcategory' => ['nullable', 'string', 'max:120'],
            'description_html' => ['required', 'string'],
            'youtube_url' => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'status' => ['required', Rule::in(AcademiaLabels::statuses())],
            'is_visible_to_clients' => ['nullable', 'boolean'],
            'attachment_files' => ['nullable', 'array', 'max:10'],
            'attachment_files.*' => ['file', 'max:10240'],
        ];
    }
}
