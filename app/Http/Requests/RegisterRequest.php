<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// FormRequest = input filter. Dirty requests rejected at gate (422), never reach controller.
// Usage: type-hint in controller, take $request->validated().
class RegisterRequest extends FormRequest
{
    // Who may use? true = anyone may register.
    public function authorize(): bool
    {
        return true;
    }

    // Input rules.
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:100',
        ];
    }
}
