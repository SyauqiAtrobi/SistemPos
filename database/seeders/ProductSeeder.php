<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed 10 products with supporting categories.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Floral',
                'slug' => 'floral',
                'description' => 'Aroma bunga yang lembut dan elegan.',
            ],
            [
                'name' => 'Woody',
                'slug' => 'woody',
                'description' => 'Nuansa kayu hangat dan maskulin.',
            ],
            [
                'name' => 'Fresh',
                'slug' => 'fresh',
                'description' => 'Aroma segar citrus dan aquatic.',
            ],
            [
                'name' => 'Oriental',
                'slug' => 'oriental',
                'description' => 'Rempah manis yang kuat dan mewah.',
            ],
        ];

        $categoryMap = [];

        foreach ($categories as $category) {
            $saved = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );

            $categoryMap[$category['slug']] = $saved->id;
        }

        $products = [
            ['name' => 'Sakura Bloom', 'slug' => 'sakura-bloom', 'category' => 'floral', 'price' => 185000, 'stock' => 20, 'description' => 'Aroma bunga sakura ringan untuk harian.'],
            ['name' => 'Rose Velvet', 'slug' => 'rose-velvet', 'category' => 'floral', 'price' => 210000, 'stock' => 15, 'description' => 'Mawar manis dengan sentuhan musk lembut.'],
            ['name' => 'Cedar Night', 'slug' => 'cedar-night', 'category' => 'woody', 'price' => 235000, 'stock' => 12, 'description' => 'Kayu cedar hangat untuk karakter tegas.'],
            ['name' => 'Sandal Aura', 'slug' => 'sandal-aura', 'category' => 'woody', 'price' => 225000, 'stock' => 18, 'description' => 'Sandalwood creamy yang calming.'],
            ['name' => 'Ocean Mist', 'slug' => 'ocean-mist', 'category' => 'fresh', 'price' => 195000, 'stock' => 25, 'description' => 'Nuansa laut segar dan clean.'],
            ['name' => 'Lemon Zest', 'slug' => 'lemon-zest', 'category' => 'fresh', 'price' => 175000, 'stock' => 30, 'description' => 'Citrus bright yang energizing.'],
            ['name' => 'Amber Spice', 'slug' => 'amber-spice', 'category' => 'oriental', 'price' => 245000, 'stock' => 10, 'description' => 'Amber hangat dengan rempah elegan.'],
            ['name' => 'Midnight Oud', 'slug' => 'midnight-oud', 'category' => 'oriental', 'price' => 275000, 'stock' => 8, 'description' => 'Oud intens untuk acara malam.'],
            ['name' => 'Jasmine Whisper', 'slug' => 'jasmine-whisper', 'category' => 'floral', 'price' => 205000, 'stock' => 14, 'description' => 'Jasmine ringan dan feminin.'],
            ['name' => 'Aqua Breeze', 'slug' => 'aqua-breeze', 'category' => 'fresh', 'price' => 190000, 'stock' => 22, 'description' => 'Fresh aquatic modern dan versatile.'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $categoryMap[$product['category']],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'description' => $product['description'],
                ]
            );
        }
    }
}
