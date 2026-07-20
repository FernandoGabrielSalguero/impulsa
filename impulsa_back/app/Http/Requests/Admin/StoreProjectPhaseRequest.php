<?php

namespace App\Http\Requests\Admin;

use App\Support\ProjectLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectPhaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $projectId = (int) $this->route('project')?->id;

        return [
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'duration_days' => ['nullable', 'integer', 'min:0'],
            'phase_order' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(ProjectLabels::phaseStatuses())],
            'assigned_user_id' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('project_collaborators', 'user_auth_id')->where(
                    static fn ($query) => $query->where('project_id', $projectId),
                ),
            ],
        ];
    }
}
