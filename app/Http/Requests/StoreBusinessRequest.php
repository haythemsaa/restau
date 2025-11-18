<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'array'],
            'address.street' => ['nullable', 'string'],
            'address.city' => ['nullable', 'string'],
            'address.postal_code' => ['nullable', 'string'],
            'address.country' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'group_id' => ['nullable', 'uuid', 'exists:business_groups,id'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
