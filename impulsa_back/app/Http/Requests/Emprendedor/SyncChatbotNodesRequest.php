<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class SyncChatbotNodesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nodes' => ['required', 'array'],
            'nodes.*.client_key' => ['nullable', 'string', 'max:80'],
            'nodes.*.title' => ['required', 'string', 'max:180'],
            'nodes.*.body' => ['required', 'string', 'max:10000'],
            'nodes.*.sort_order' => ['nullable', 'integer', 'min:1'],
            'nodes.*.is_start' => ['nullable', 'boolean'],
            'nodes.*.status' => ['nullable', 'in:active,inactive'],
            'nodes.*.options' => ['nullable', 'array'],
            'nodes.*.options.*.label' => ['required_with:nodes.*.options', 'string', 'max:180'],
            'nodes.*.options.*.action_type' => ['nullable', 'in:go_to_node,whatsapp,restart,close'],
            'nodes.*.options.*.target_client_key' => ['nullable', 'string', 'max:80'],
            'nodes.*.options.*.sort_order' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
