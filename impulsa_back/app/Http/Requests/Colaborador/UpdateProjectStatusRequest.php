<?php

namespace App\Http\Requests\Colaborador;

use App\Support\ProjectLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(ProjectLabels::statuses())],
        ];
    }
}
