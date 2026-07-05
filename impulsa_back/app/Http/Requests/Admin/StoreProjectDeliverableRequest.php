<?php

namespace App\Http\Requests\Admin;

use App\Support\ProjectLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectDeliverableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'phase_id' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'deliverable_type' => ['required', 'string', Rule::in(ProjectLabels::deliverableTypes())],
            'status' => ['required', 'string', Rule::in(ProjectLabels::deliverableStatuses())],
            'due_date' => ['nullable', 'date'],
            'client_visible' => ['required', 'boolean'],
        ];
    }
}
