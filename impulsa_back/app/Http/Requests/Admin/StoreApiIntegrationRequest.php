<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiIntegrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:180'],
            'allowed_domain' => ['required', 'string', 'max:190'],
            'user_auth_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('user_auth_id') && $this->input('user_auth_id') === '') {
            $this->merge(['user_auth_id' => null]);
        }
    }
}
