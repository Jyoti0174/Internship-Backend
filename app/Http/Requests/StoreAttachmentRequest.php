<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'A file is required to upload an attachment.',
            'file.file' => 'The uploaded item must be a valid file.',
            'file.max' => 'The file size must not exceed 10240 KB.',
            'file.mimes' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are allowed.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422));
    }
}