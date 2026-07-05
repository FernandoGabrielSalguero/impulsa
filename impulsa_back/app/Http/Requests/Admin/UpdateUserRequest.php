<?php

namespace App\Http\Requests\Admin;

use App\Support\UserMenuCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = (int) $this->route('user')->id;

        return [
            'correo' => ['required', 'email', 'max:255', Rule::unique('user_auth', 'correo')->ignore($userId)],
            'rol' => ['required', Rule::in($this->roles())],
            'usuario_tipo' => ['required', Rule::in(['interno', 'externo'])],
            'nombre' => ['nullable', 'string', 'max:100'],
            'apellido' => ['nullable', 'string', 'max:100'],
            'apodo' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date_format:Y-m-d'],
            'correo_contacto' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'correo_verificado' => ['required', 'boolean'],
            'permison_correo' => ['required', 'boolean'],
            'permison_whatsapp' => ['required', 'boolean'],
            'pagina_inicio' => ['nullable', 'string', Rule::in($this->pageOptions())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => strtolower(trim((string) $this->input('correo'))),
            'correo_contacto' => strtolower(trim((string) $this->input('correo_contacto'))),
            'nombre' => trim((string) $this->input('nombre')),
            'apellido' => trim((string) $this->input('apellido')),
            'apodo' => trim((string) $this->input('apodo')),
            'whatsapp' => trim((string) $this->input('whatsapp')),
            'pagina_inicio' => trim((string) $this->input('pagina_inicio')),
            'usuario_tipo' => trim((string) $this->input('usuario_tipo', 'externo')),
            'correo_verificado' => $this->boolean('correo_verificado'),
            'permison_correo' => $this->boolean('permison_correo'),
            'permison_whatsapp' => $this->boolean('permison_whatsapp'),
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

    private function pageOptions(): array
    {
        $role = (string) $this->input('rol', $this->route('user')->rol);

        return array_merge([''], UserMenuCatalog::keysForRole($role));
    }
}
