<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Auth flow test: register -> login -> me -> logout.
// Run: php artisan test --filter=AuthTest
// Rule: 1 method = 1 story. Do not mix login + no-token in 1 method
// (auth state sticks between requests in 1 test).
class AuthTest extends TestCase
{
    use RefreshDatabase; // DB reset each test, production data safe.

    // Create user directly in DB (no HTTP), then login via HTTP.
    private function createUser(): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);
    }

    private function loginGetToken(): string
    {
        $this->createUser();

        $res = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ])->assertStatus(200)->assertJsonPath('ok', true);

        return $res->json('data.token');
    }

    public function test_register_success(): void
    {
        $res = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $res->assertStatus(201)->assertJsonPath('ok', true);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_register_invalid_input_422(): void
    {
        $res = $this->postJson('/api/v1/register', ['email' => 'xx']);

        $res->assertStatus(422)->assertJsonPath('ok', false);
    }

    public function test_login_wrong_password_422(): void
    {
        $this->createUser();

        $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422)->assertJsonPath('ok', false);
    }

    public function test_me_with_token(): void
    {
        $token = $this->loginGetToken();

        // With token -> 200.
        $this->getJson('/api/v1/me', ['Authorization' => "Bearer $token"])
            ->assertStatus(200)->assertJsonPath('data.email', 'test@example.com');
    }

    public function test_me_without_token_401(): void
    {
        // Standalone test (fresh DB, not logged in) -> 401.
        $this->getJson('/api/v1/me')->assertStatus(401)->assertJsonPath('ok', false);
    }

    public function test_logout_revokes_token(): void
    {
        $token = $this->loginGetToken();
        $user = User::where('email', 'test@example.com')->first();

        // 1 token before logout.
        $this->assertEquals(1, $user->tokens()->count());

        // Logout -> 200.
        $this->postJson('/api/v1/logout', [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        // Token gone in DB (logout really deletes). Check DB, not another request,
        // because in 1 test Laravel auth sticks (2nd request passes even if token dead).
        $this->assertEquals(0, $user->fresh()->tokens()->count());
    }
}
