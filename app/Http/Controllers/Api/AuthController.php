<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

// Auth entry point: register, login (issue token), profile, logout.
class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $auth) {}

    // POST /api/v1/register
    public function register(RegisterRequest $request)
    {
        // $request->validated() = data already clean, passed validation.
        $user = $this->auth->register($request->validated());

        return $this->ok(new UserResource($user), 'Registration successful', 201);
    }

    // POST /api/v1/login
    public function login(LoginRequest $request)
    {
        // Wrong email/password = automatic 422 from service.
        $result = $this->auth->login($request->email, $request->password);

        return $this->ok([
            'user' => new UserResource($result['user']),
            'token' => $result['token'], // simpan di client, kirim via header Bearer
        ], 'Login successful');
    }

    // GET /api/v1/me (token required)
    public function me(Request $request)
    {
        return $this->ok(new UserResource($request->user()), 'OK');
    }

    // POST /api/v1/logout (token required). Delete ALL user tokens for clean state.
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->ok(null, 'Logout successful');
    }
}
