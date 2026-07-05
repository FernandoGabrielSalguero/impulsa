<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correo' => ['required', 'email', 'max:255', 'unique:user_auth,correo'],
            'rol' => ['required', Rule::in($this->roles())],
            'nombre' => ['nullable', 'string', 'max:100'],
            'apellido' => ['nullable', 'string', 'max:100'],
            'apodo' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => strtolower(trim((string) $this->input('correo'))),
            'nombre' => trim((string) $this->input('nombre')),
            'apellido' => trim((string) $this->input('apellido')),
            'apodo' => trim((string) $this->input('apodo')),
            'whatsapp' => trim((string) $this->input('whatsapp')),
        ]);
    }

    private function roles(): array
    {
        return [
            'impulsa_administrador',
            'impulsa_colaborador',
            'impulsa_emprendedor',
            'impulsa_usuario',
            'impulsa_marketing',
            'impulsa_cliente',
        ];
    }
}
