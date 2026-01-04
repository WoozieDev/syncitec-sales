<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->first();
        if (!$category) return;

        $brand = Brand::query()->first();

        $products = [
            ['sku' => 'TCG-POK-001', 'name' => 'Booster Pokémon (Demo)', 'price' => 1990, 'stock' => 12],
            ['sku' => 'FIG-ANI-001', 'name' => 'Figura Anime (Demo)', 'price' => 8990, 'stock' => 3],
            ['sku' => 'ACC-BIN-001', 'name' => 'Binder TCG (Demo)', 'price' => 5990, 'stock' => 7],
        ];

        foreach ($products as $p) {
            $baseSlug = Str::slug($p['name']);
            $slug = $baseSlug;
            $i = 2;

            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$i}";
                $i++;
            }

            $product = Product::firstOrCreate(
                ['sku' => $p['sku']],
                [
                    'category_id' => $category->id,
                    'brand_id' => $brand?->id,
                    'name' => $p['name'],
                    'slug' => $slug,
                    'description' => 'Producto de prueba para el MVP.',
                    'price_amount' => $p['price'], // cents
                    'currency' => 'PEN',
                    'stock_on_hand' => $p['stock'],
                    'is_active' => true,
                ]
            );

            // fake image path (later we’ll implement upload)
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'path' => 'products/demo.png'],
                [
                    'sort_order' => 0,
                    'is_primary' => true,
                ]
            );
        }
    }
}
