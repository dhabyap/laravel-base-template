<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Auth business logic: register + login + token.
class AuthService
{
    // Register new account. Default role 'user'.
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // auto hash via 'hashed' cast
            'role' => 'user',
        ]);
    }

    // Login: check email + password, issue token. Wrong = 422.
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        // Delete old tokens to avoid pile-up (optional, safe).
        $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}
