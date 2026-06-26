<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'The comment field is required.',
            'body.min' => 'The comment must be at least 2 characters.',
        ];
    }
}