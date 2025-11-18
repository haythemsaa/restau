<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_id' => ['required', 'uuid', 'exists:businesses,id'],
            'platform' => ['required', 'string', 'max:50'],
            'platform_review_id' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'text' => ['nullable', 'string'],
            'response_text' => ['nullable', 'string'],
            'sentiment_score' => ['nullable', 'numeric', 'min:-1', 'max:1'],
            'categories' => ['nullable', 'array'],
        ];
    }
}
