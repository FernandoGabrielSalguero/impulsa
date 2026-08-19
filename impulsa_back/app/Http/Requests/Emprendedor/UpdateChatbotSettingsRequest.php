<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChatbotSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'avatar_url' => ['nullable', 'string', 'max:255'],
            'avatar_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['sometimes', 'boolean'],
            'icon_background_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'whatsapp' => ['required', 'string', 'max:80'],
            'initial_message' => ['required', 'string', 'max:5000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('remove_avatar')) {
            $this->merge([
                'remove_avatar' => filter_var($this->input('remove_avatar'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
