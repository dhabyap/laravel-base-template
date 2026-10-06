<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

// Seed initial DB for dev. Run: php artisan db:seed
// Accounts: admin@example.com / password123 (admin), user@example.com / password123
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin: delete first if exists so seed stays idempotent.
        User::where('email', 'admin@example.com')->delete();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123', // auto hash via casts
            'role' => 'admin',
        ]);

        // Regular user for login tryout.
        User::where('email', 'user@example.com')->delete();
        User::create([
            'name' => 'User Demo',
            'email' => 'user@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        // Sample products (clear first to avoid doubles).
        Product::truncate();
        Product::insert([
            ['name' => 'Sample Coffee', 'price' => 15000, 'stock' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sample Tea', 'price' => 8000, 'stock' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sample Noodles', 'price' => 12000, 'stock' => 30, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
