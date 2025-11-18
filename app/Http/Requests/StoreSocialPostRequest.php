<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_id' => ['required', 'uuid', 'exists:businesses,id'],
            'platforms' => ['required', 'array', 'min:1'],
            'platforms.*' => ['string', 'in:facebook,instagram,twitter,linkedin,tiktok'],
            'content' => ['nullable', 'string'],
            'media_urls' => ['nullable', 'array'],
            'media_urls.*' => ['url'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'status' => ['sometimes', 'in:draft,scheduled,publishing,published,failed'],
        ];
    }
}
