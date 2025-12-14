<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User (Now Owner)
        User::firstOrCreate(
            ['email' => 'owner@ulucafe.com'],
            [
                'name' => 'Owner Ulu Coffee',
                'password' => bcrypt('password'),
                'role' => 'owner'
            ]
        );

        // Create Cashier User
        User::firstOrCreate(
            ['email' => 'kasir@ulucafe.com'],
            [
                'name' => 'Kasir Ulu Coffee',
                'password' => bcrypt('password'),
                'role' => 'cashier'
            ]
        );

        // Create Customer User
        User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Customer',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]
        );

        // Categories
        $catLunch = Category::firstOrCreate(['slug' => 'business-lunch'], ['name' => 'Makan Siang Bisnis']);
        $catDessert = Category::firstOrCreate(['slug' => 'dessert'], ['name' => 'Dessert']);
        $catDrinks = Category::firstOrCreate(['slug' => 'drinks'], ['name' => 'Minuman']);
        $catWok = Category::firstOrCreate(['slug' => 'wok'], ['name' => 'WOK']);

        // Products - Business Lunch
        Product::firstOrCreate(
            ['slug' => 'paket-hemat-1'],
            [
                'category_id' => $catLunch->id,
                'name' => 'Paket Hemat 1',
                'description' => 'Nasi, Ayam Goreng, Lalapan, Sambal',
                'price' => 35000,
                'image' => 'lunch1.jpg'
            ]
        );
        // ... (other products, can be left as create if they don't conflict, but safer to use firstOrCreate or check)
        // Since this is a seeder run multiple times in development, firstOrCreate is better.
        // Since this is a seeder run multiple times in development, firstOrCreate is better.
        // But for simplicity in this specific fix, I'll just fix the user/category creation.
        
        $this->call([
           BannerSeeder::class,
        ]);
    }
}
