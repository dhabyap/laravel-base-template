<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Login filter. Throttle (attempt limit) set in route, not here.
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }
}
