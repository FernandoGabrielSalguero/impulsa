<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinanceMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'required', 'in:ingreso,egreso,inversion'],
            'category_id' => ['sometimes', 'required', 'integer', 'min:1'],
            'amount' => ['sometimes', 'required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'occurred_on' => ['sometimes', 'required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
