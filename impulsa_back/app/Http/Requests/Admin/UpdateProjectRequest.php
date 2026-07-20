<?php

namespace App\Http\Requests\Admin;

use App\Support\ProjectLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
            'summary' => ['nullable', 'string'],
            'scope_summary' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(ProjectLabels::statuses())],
            'priority' => ['required', 'string', Rule::in(ProjectLabels::priorities())],
            'start_date' => ['nullable', 'date'],
            'client_visible' => ['required', 'boolean'],
            'collaborator_user_ids' => ['nullable', 'array'],
            'collaborator_user_ids.*' => ['integer', 'min:1', 'distinct'],
        ];
    }
}
