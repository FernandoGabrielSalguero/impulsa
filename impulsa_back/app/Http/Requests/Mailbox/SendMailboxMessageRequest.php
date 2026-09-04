<?php

namespace App\Http\Requests\Mailbox;

use Illuminate\Foundation\Http\FormRequest;

class SendMailboxMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'to' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'to.required' => 'Ingresá el destinatario.',
            'to.email' => 'Ingresá un destinatario válido.',
            'subject.required' => 'Ingresá el asunto.',
            'body.required' => 'Escribí el mensaje.',
        ];
    }
}
