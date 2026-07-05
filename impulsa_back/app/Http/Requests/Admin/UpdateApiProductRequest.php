<?php

namespace App\Http\Requests\Admin;

class UpdateApiProductRequest extends StoreApiProductRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['api_integration_id']);

        return $rules;
    }
}
