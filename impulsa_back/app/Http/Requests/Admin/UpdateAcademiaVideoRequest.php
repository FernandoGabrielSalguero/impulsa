<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademiaVideoRequest extends StoreAcademiaVideoRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'remove_attachment_ids' => ['nullable'],
            'remove_attachment_ids.*' => ['integer', 'min:1'],
        ]);
    }
}
