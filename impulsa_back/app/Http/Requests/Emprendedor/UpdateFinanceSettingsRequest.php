<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinanceSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'currency' => ['sometimes', 'required', 'string', 'max:8'],
            'opening_balance' => ['sometimes', 'required', 'numeric', 'min:-9999999999.99', 'max:9999999999.99'],
        ];
    }
}
