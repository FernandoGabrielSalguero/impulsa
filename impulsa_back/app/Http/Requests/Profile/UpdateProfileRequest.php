<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nombre' => ['nullable', 'string', 'max:100'],
            'apellido' => ['nullable', 'string', 'max:100'],
            'apodo' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date_format:Y-m-d'],
            'correo_contacto' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'permison_correo' => ['required', 'boolean'],
            'permison_whatsapp' => ['required', 'boolean'],
            'avatar_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->input('nombre')),
            'apellido' => trim((string) $this->input('apellido')),
            'apodo' => trim((string) $this->input('apodo')),
            'correo_contacto' => strtolower(trim((string) $this->input('correo_contacto'))),
            'whatsapp' => trim((string) $this->input('whatsapp')),
            'permison_correo' => $this->boolean('permison_correo'),
            'permison_whatsapp' => $this->boolean('permison_whatsapp'),
        ]);

        if ($this->has('remove_avatar')) {
            $this->merge([
                'remove_avatar' => filter_var($this->input('remove_avatar'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
