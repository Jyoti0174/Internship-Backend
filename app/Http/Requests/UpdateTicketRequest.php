<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => 'sometimes|required|string|min:5|max:255',
            'description'   => 'sometimes|required|string|min:10',
            'status'        => 'sometimes|in:open,in_progress,closed',
            'priority'      => 'sometimes|in:low,medium,high',
            'user_id'       => 'sometimes|required|exists:users,id',
            'assigned_to'   => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'            => 'Title must be at least 5 characters.',
            'title.max'            => 'Title cannot exceed 255 characters.',
            'description.min'      => 'Description must be at least 10 characters.',
            'status.in'   => 'Status must be one of: Open, In Progress, or Closed.',
            'priority.in' => 'Priority must be one of: Low, Medium, or High.',
            'user_id.exists'       => 'Selected user does not exist.',
            'assigned_to.exists'   => 'Assigned user does not exist.',
            'department_id.exists' => 'Selected department does not exist.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
