<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;

class TestResources
{
    public static function createXenoblade()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategorynintendo = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'category_id' => $category->id,
        ]);

        $brandmonolith = Brand::create([
            'name' => 'Monolith Soft',
            'slug' => 'monolith',
            'category_id' => $category->id,
        ]);

        $xenoblade = Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'slug' => 'xenoblade',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategorynintendo->id,
            'brand_id' => $brandmonolith->id,
        ]);

        $xenoblade->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        return $xenoblade;
    }

    public static function createHearts()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategorysony = Subcategory::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'category_id' => $category->id,
        ]);

        $brandsquare = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $hearts = Product::create([
            'name' => 'Kingdom Hearts',
            'slug' => 'hearts',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategorysony->id,
            'brand_id' => $brandsquare->id,
        ]);

        $hearts->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        return $hearts;
    }

    public static function createTwewy()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategorysony = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'category_id' => $category->id,
        ]);

        $brandsquare = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $twewy = Product::create([
            'name' => 'The World Ends With You',
            'slug' => 'twewy',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategorysony->id,
            'brand_id' => $brandsquare->id,
        ]);

        $twewy->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        return $twewy;
    }

}
