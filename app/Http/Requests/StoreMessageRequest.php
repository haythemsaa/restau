<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['required', 'uuid', 'exists:conversations,id'],
            'direction' => ['required', 'in:inbound,outbound'],
            'sender_type' => ['required', 'in:customer,agent,bot'],
            'sender_id' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'content.text' => ['sometimes', 'string'],
            'content.type' => ['sometimes', 'string'],
        ];
    }
}
