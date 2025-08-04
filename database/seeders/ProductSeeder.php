<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = public_path('products.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData['products'] as $product) {
            \App\Models\Product::create([
                'name' => $product['name'],
                'price' => $product['price'],
                'tagline' => $product['tagline'] ?? null,
                'description' => $product['description'],
                'features' => $product['features'] ?? null,
                'image' => $product['image'],
                'texture' => $product['texture'] ?? null,
                'color' => $product['color'] ?? null,
                'scent' => $product['scent'] ?? null,
                'size' => $product['size'],
                'category' => $product['category'],
                'ingredients' => $product['ingredients'],
                'perfect_for' => $product['Perfect_for'] ?? $product['perfect_for'] ?? 'All skin types',
                'is_featured' => false,
                'is_active' => true
            ]);
        }
    }
}
