<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreProjectRequest extends FormRequest
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
            'manager_user_id' => ['required', 'integer', 'min:1'],
            'client_user_id' => ['nullable', 'integer', 'min:1', 'required_without:create_client'],
            'create_client' => ['nullable', 'array', 'required_without:client_user_id'],
            'create_client.correo' => ['required_with:create_client', 'email', 'max:255', 'unique:user_auth,correo'],
            'create_client.nombre' => ['nullable', 'string', 'max:100'],
            'create_client.apellido' => ['nullable', 'string', 'max:100'],
            'create_client.apodo' => ['nullable', 'string', 'max:100'],
            'create_client.whatsapp' => ['nullable', 'string', 'max:30'],
            'summary' => ['nullable', 'string'],
            'scope_summary' => ['nullable', 'string'],
            'client_visible' => ['nullable', 'boolean'],
            'collaborator_user_ids' => ['nullable', 'array'],
            'collaborator_user_ids.*' => ['integer', 'min:1', 'distinct'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->filled('client_user_id') && $this->filled('create_client')) {
                $validator->errors()->add('client_user_id', 'Indicá un cliente existente o uno nuevo, no ambos.');
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $createClient = $this->input('create_client');

        if (! is_array($createClient)) {
            return;
        }

        $this->merge([
            'create_client' => [
                'correo' => strtolower(trim((string) ($createClient['correo'] ?? ''))),
                'nombre' => trim((string) ($createClient['nombre'] ?? '')),
                'apellido' => trim((string) ($createClient['apellido'] ?? '')),
                'apodo' => trim((string) ($createClient['apodo'] ?? '')),
                'whatsapp' => trim((string) ($createClient['whatsapp'] ?? '')),
            ],
        ]);
    }
}
