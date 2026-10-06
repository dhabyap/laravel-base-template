<?php

namespace App\Traits;

// This trait is used by all API controllers.
// Goal: response format ALWAYS consistent.
trait ApiResponse
{
    // Success response. Example: return $this->ok($user, 'Login successful');
    protected function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    // Failure response. Example: return $this->fail('Insufficient balance', 422);
    protected function fail(string $message = 'Failed', int $code = 400, $errors = null)
    {
        return response()->json([
            'ok' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
