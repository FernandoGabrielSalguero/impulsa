<?php

namespace App\Http\Requests\Admin;

use App\Support\TaskLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nombre_tarea' => ['required', 'string', 'max:180'],
            'responsable_user_id' => ['required', 'integer', 'min:1'],
            'descripcion' => ['required', 'string'],
            'fecha_entrega' => ['required', 'date'],
            'prioridad_defcon' => ['required', 'integer', Rule::in(TaskLabels::defconLevels())],
            'reporta_a' => ['required', 'string', 'max:180'],
            'estado' => ['required', 'string', Rule::in(TaskLabels::statuses())],
        ];
    }
}
