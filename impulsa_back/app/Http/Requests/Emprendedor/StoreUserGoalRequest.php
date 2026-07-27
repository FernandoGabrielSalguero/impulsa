<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'start_date' => $this->input('start_date') ?: null,
            'due_date' => $this->input('due_date') ?: null,
            'description' => $this->input('description') ?: null,
        ]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['nullable', 'date'],
            'due_date' => array_merge(
                ['nullable', 'date'],
                $this->filled('start_date') ? ['after_or_equal:start_date'] : [],
            ),
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'due_date.after_or_equal' => 'La fecha límite debe ser posterior o igual a la fecha de inicio.',
        ];
    }
}
