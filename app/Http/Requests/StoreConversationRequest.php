<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_id' => ['required', 'uuid', 'exists:businesses,id'],
            'channel' => ['required', 'in:facebook,instagram,whatsapp,google,email,sms'],
            'customer_id' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'assigned_to' => ['nullable', 'uuid', 'exists:users,id'],
            'tags' => ['nullable', 'array'],
        ];
    }
}
