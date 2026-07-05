<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentViewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'content_type' => ['required', 'in:blog_post,product'],
            'content_id' => ['required', 'integer', 'min:1'],
            'page_url' => ['nullable', 'string', 'max:500'],
        ];
    }
}
