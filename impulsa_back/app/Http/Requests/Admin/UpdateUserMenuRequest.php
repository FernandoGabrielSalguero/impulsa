<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu_keys' => ['required', 'array', 'min:1'],
            'menu_keys.*' => ['required', 'string'],
        ];
    }
}
