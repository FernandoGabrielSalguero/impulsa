<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]+$/i'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
