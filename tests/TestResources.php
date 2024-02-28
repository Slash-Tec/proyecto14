<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;

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

    public static function createHalo()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategorysony = Subcategory::create([
            'name' => 'Xbox',
            'slug' => 'xbox',
            'category_id' => $category->id,
        ]);

        $brandsquare = Brand::create([
            'name' => 'Bungie',
            'slug' => 'bungie',
            'category_id' => $category->id,
        ]);

        $halo = Product::create([
            'name' => 'Halo',
            'slug' => 'halo',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategorysony->id,
            'brand_id' => $brandsquare->id,
        ]);

        $halo->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        return $halo;
    }

    public static function createMobile()
    {
        $category = Category::create([
            'name' => 'Celulares y tablets',
            'slug' => 'celulares-y-tablets',
            'icon' => '<i class="fas fa-mobile"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategorymobile = Subcategory::create([
            'name' => 'Tablet',
            'slug' => 'tablet',
            'category_id' => $category->id,
        ]);

        $brandmobile = Brand::create([
            'name' => 'Apple',
            'slug' => 'apple',
            'category_id' => $category->id,
        ]);

        $tablet = Product::create([
            'name' => 'Tableta',
            'slug' => 'tableta',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategorymobile->id,
            'brand_id' => $brandmobile->id,
        ]);

        $tablet->images()->create([
            'url' => 'tests/example.jpg',
            'imageable_type' => Product::class,
            'imageable_id' => $tablet->id,
        ]);

        $colors = [
            'Rojo',
            'Azul',
            'Verde',
        ];

        foreach ($colors as $colorName) {
            $color = Color::create(['name' => $colorName]);
            $tablet->colors()->attach($color->id, ['quantity' => 10]);
        }

        $availableColors = Color::all();

        return $tablet;
    }

    public static function createShirt()
    {
        $category = Category::create([
            'name' => 'Ropa',
            'slug' => 'ropa',
            'icon' => '<i class="fas fa-tshirt"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategoryclothes = Subcategory::create([
            'name' => 'Camiseta',
            'slug' => 'camiseta',
            'category_id' => $category->id,
        ]);

        $brandclothes = Brand::create([
            'name' => 'Bungie',
            'slug' => 'bungie',
            'category_id' => $category->id,
        ]);

        $shirt = Product::create([
            'name' => 'Camiseta',
            'slug' => 'camiseta',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategoryclothes->id,
            'brand_id' => $brandclothes->id,
        ]);

        $shirt->images()->create([
            'url' => 'tests/example.jpg',
            'imageable_type' => Product::class,
            'imageable_id' => $shirt->id,
        ]);

        $colors = ['Rojo', 'Azul', 'Verde'];

        foreach ($colors as $colorName) {
            $color = Color::create(['name' => $colorName]);
            $shirt->colors()->attach($color->id, ['quantity' => 10]);
        }

        $sizes = ['S', 'M', 'L'];
        $sizeIds = [];

        foreach ($sizes as $sizeName) {
            $size = Size::create(['name' => $sizeName, 'product_id' => $shirt->id]);

            if ($size) {
                $sizeIds[] = $size->id;
                $shirt->sizes()->attach($size->id, ['quantity' => 10]);
            } else {
                \Illuminate\Support\Facades\Log::error('Error creando talla para la camiseta');
            }
        }

        $availableColors = Color::all();
        $availableSizes = Size::all();

        return [
            'shirt' => $shirt->fresh(),
            'size_ids' => $sizeIds,
        ];
    }

    public static function user()
    {
        $user = User::create([
            'name' => 'pepe',
            'email' => 'pepe@ejemplo.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
