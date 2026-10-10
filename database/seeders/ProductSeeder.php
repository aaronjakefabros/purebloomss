<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $flowers = [
            ['name' => 'Red Roses Bouquet', 'description' => 'Classic dozen red roses, perfect for anniversaries.', 'price' => 1500.00, 'stock' => 20, 'category' => 'Roses', 'image' => 'roses.jpg'],
            ['name' => 'Sunflower Bundle', 'description' => 'Bright and cheerful sunflowers to lift anyone\'s day.', 'price' => 800.00, 'stock' => 15, 'category' => 'Sunflowers', 'image' => 'sunflower.jpg'],
            ['name' => 'White Lilies Arrangement', 'description' => 'Elegant white lilies for condolences or celebrations.', 'price' => 1200.00, 'stock' => 10, 'category' => 'Lilies', 'image' => 'lilies.jpg'],
            ['name' => 'Mixed Bloom Basket', 'description' => 'A colorful mix of seasonal flowers in a woven basket.', 'price' => 1800.00, 'stock' => 8, 'category' => 'Mixed', 'image' => 'mixed.jpg'],
            ['name' => 'Pink Peonies', 'description' => 'Soft pink peonies, symbolizing romance and prosperity.', 'price' => 2000.00, 'stock' => 12, 'category' => 'Peonies', 'image' => 'peonies.jpg'],
            ['name' => 'Tulip Garden Set', 'description' => 'Fresh tulips in assorted colors.', 'price' => 1000.00, 'stock' => 18, 'category' => 'Tulips', 'image' => 'tulips.jpg'],
        ];

        foreach ($flowers as $flower) {
            Product::create($flower);
        }
    }
}