<?php

namespace App\Http\Requests\Emprendedor;

use App\Support\GoalLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('start_date')) {
            $merge['start_date'] = $this->input('start_date') ?: null;
        }

        if ($this->has('due_date')) {
            $merge['due_date'] = $this->input('due_date') ?: null;
        }

        if ($this->has('description')) {
            $merge['description'] = $this->input('description') ?: null;
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'status' => ['sometimes', Rule::in(GoalLabels::statuses())],
        ];
    }
}
