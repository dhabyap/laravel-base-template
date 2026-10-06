<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Product CRUD test: create -> list -> update -> delete.
// Run: php artisan test --filter=ProductTest
class ProductTest extends TestCase
{
    use RefreshDatabase;

    // Create user + ready token.
    private function token(): string
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        return $user->createToken('test')->plainTextToken;
    }

    public function test_product_crud(): void
    {
        $h = ['Authorization' => 'Bearer '.$this->token()];

        // No token -> 401.
        $this->getJson('/api/v1/products')->assertStatus(401);

        // Create -> 201.
        $create = $this->postJson('/api/v1/products', [
            'name' => 'Sample Product', 'price' => 15000, 'stock' => 10,
        ], $h)->assertStatus(201)->assertJsonPath('ok', true);
        $id = $create->json('data.id');

        // Bad input -> 422.
        $this->postJson('/api/v1/products', ['name' => ''], $h)
            ->assertStatus(422)->assertJsonPath('ok', false);

        // List has 1.
        $this->getJson('/api/v1/products', $h)
            ->assertStatus(200)->assertJsonPath('ok', true);

        // Detail.
        $this->getJson("/api/v1/products/$id", $h)->assertStatus(200);

        // Update price.
        $this->putJson("/api/v1/products/$id", ['price' => 20000], $h)
            ->assertStatus(200)->assertJsonPath('data.price', 20000);

        // Delete.
        $this->deleteJson("/api/v1/products/$id", [], $h)->assertStatus(200);
        $this->getJson("/api/v1/products/$id", $h)->assertStatus(404);
    }
}
