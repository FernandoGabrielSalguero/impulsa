<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:2', 'max:100'],
            'correo' => ['required', 'email', 'max:255', 'unique:user_auth,correo'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'perfil' => ['required', Rule::in(['emprendedor', 'cliente'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => strtolower(trim((string) $this->input('correo'))),
            'nombre' => trim((string) $this->input('nombre')),
        ]);
    }

    public function messages(): array
    {
        return [
            'correo.unique' => 'Ya existe un usuario registrado con ese correo.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'nombre.min' => 'Ingresá un nombre válido.',
        ];
    }
}
