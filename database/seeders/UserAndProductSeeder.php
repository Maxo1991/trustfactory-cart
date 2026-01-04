<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;

class UserAndProductSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ USERS
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        // ✅ PRODUCTS
        Product::updateOrCreate(
            ['name' => 'Product One'],
            [
                'price' => 10.00,
                'stock' => 20,
                'low_stock_threshold' => 5,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Product Two'],
            [
                'price' => 15.00,
                'stock' => 20,
                'low_stock_threshold' => 5,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Product Tree'],
            [
                'price' => 20.00,
                'stock' => 20,
                'low_stock_threshold' => 5,
            ]
        );
    }
}
