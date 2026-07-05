<?php

namespace App\Http\Requests\Admin;

use App\Support\ApiProductLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApiProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'api_integration_id' => ['required', 'integer', 'exists:api_integrations,id'],
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:220'],
            'sku' => ['nullable', 'string', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:300'],
            'description_html' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:120'],
            'subcategory' => ['nullable', 'string', 'max:120'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:8'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'availability' => ['required', Rule::in(ApiProductLabels::availabilities())],
            'status' => ['required', Rule::in(ApiProductLabels::statuses())],
            'featured' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:999999'],
            'metadata_json' => ['nullable', 'string'],
            'main_image_file' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
            'thumbnail_file' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
            'attachment_file' => ['nullable', 'file', 'max:8192', 'mimes:pdf,doc,docx,txt'],
            'remove_main_image' => ['nullable', 'boolean'],
            'remove_thumbnail' => ['nullable', 'boolean'],
            'remove_attachment' => ['nullable', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'api_integration_id.required' => 'Debés seleccionar una integración API.',
            'title.required' => 'El título es obligatorio.',
            'description_html.required' => 'La descripción es obligatoria.',
            'currency.required' => 'La moneda es obligatoria.',
            'availability.required' => 'La disponibilidad es obligatoria.',
            'status.required' => 'El estado es obligatorio.',
            'sort_order.required' => 'El orden es obligatorio.',
        ];
    }
}
