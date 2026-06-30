<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'success'    => true,
            'statusCode' => $statusCode,
            'message'    => $message,
            'data'       => $data,
            'timestamp'  => now()->toIso8601String(),
        ], $statusCode);
    }

    protected function errorResponse(string $message = 'An error occurred', int $statusCode = 500, $errors = null)
    {
        $response = [
            'success'    => false,
            'statusCode' => $statusCode,
            'message'    => $message,
            'data'       => null,
            'timestamp'  => now()->toIso8601String(),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}