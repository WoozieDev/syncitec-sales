<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Root categories
        $roots = [
            'Figuras de colección',
            'TCG',
            'Accesorios',
        ];

        foreach ($roots as $i => $name) {
            $root = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'parent_id' => null,
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $i,
                ]
            );

            // Basic children examples
            $children = match ($name) {
                'Figuras de colección' => ['Anime', 'Marvel / DC', 'Videojuegos'],
                'TCG' => ['Pokémon', 'Yu-Gi-Oh!', 'Magic'],
                'Accesorios' => ['Fundas', 'Micas', 'Binders'],
                default => [],
            };

            foreach ($children as $j => $childName) {
                $slug = Str::slug($childName);

                // Ensure uniqueness under same name
                $finalSlug = Category::where('slug', $slug)->exists()
                    ? "{$slug}-{$root->id}"
                    : $slug;

                Category::firstOrCreate(
                    ['slug' => $finalSlug],
                    [
                        'parent_id' => $root->id,
                        'name' => $childName,
                        'is_active' => true,
                        'sort_order' => $j,
                    ]
                );
            }
        }
    }
}
