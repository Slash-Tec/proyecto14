<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\ColorSize;
use App\Models\Product;
use App\Models\Size;

trait CreateData
{

    private $counter = 1;

    private function generateUniqueCounter()
    {
        return $this->counter++;
    }

    public function generateCategoryData()
    {
        return [
                'name' => 'Nombre de la Categoría ' . $this->generateUniqueCounter(),
                'slug' => 'slug-de-la-categoria-' . $this->generateUniqueCounter(),
                'icon' => 'icon' . $this->generateUniqueCounter() . '.png',
                'image' => 'image' . $this->generateUniqueCounter() . '.jpg',
            ];
    }

    public function generateBrandData($category)
    {
        return [
            'name' => 'Marca' . $this->generateUniqueCounter(),
        ];
    }

    public function generateSubcategoryData($category)
    {
        return [
                'name' => 'Nombre de la Subcategoría ' . $this->generateUniqueCounter(),
                'slug' => 'slug-de-la-subcategoria-' . $this->generateUniqueCounter(),
                'category_id' => $category->id,
                'color' => rand(0, 1),
                'size' => rand(0, 1),
            ];
    }

    public function generateColorData($count)
    {
        $colors = [];
        for ($i = 1; $i <= $count; $i++) {
            $color = [
                'name' => 'Color' . $i,
            ];
            $colors[] = $color;
        }

        Color::insert($colors);
    }

    public function generateProductData($quantity, $subcategory, $brand)
    {
        $products = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $uniqueCounter = $this->generateUniqueCounter();
            $products[] = [
                'name' => 'Producto ' . $uniqueCounter,
                'slug' => 'producto-' . $uniqueCounter,
                'description' => 'Description for Product ' . $uniqueCounter,
                'price' => rand(10, 100),
                'subcategory_id' => $subcategory->id,
                'brand_id' => $brand->id,
                'quantity' => rand(1, 50),
                'sold' => rand(0, 30),
                'reserved' => rand(0, 10),
                'status' => 2,
            ];
        }

        return $products;
    }

    public function generateColorProductData($count)
    {
        $colors = Color::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();

        $colorProductData = [];
        for ($i = 1; $i <= $count; $i++) {
            $colorId = $colors[array_rand($colors)];
            $productId = $products[array_rand($products)];

            $colorProduct = [
                'color_id' => $colorId,
                'product_id' => $productId,
                'quantity' => rand(1, 10),
            ];

            $colorProductData[] = $colorProduct;
        }

        ColorProduct::insert($colorProductData);
    }

    public function generateSizeData($count)
    {
        $products = Product::pluck('id')->toArray();

        $sizes = [];
        for ($i = 1; $i <= $count; $i++) {
            $size = [
                'name' => 'Size' . $i,
                'product_id' => $products[array_rand($products)],
            ];
            $sizes[] = $size;
        }

        Size::insert($sizes);
    }

    public function generateColorSizeData($count)
    {
        $colors = Color::pluck('id')->toArray();
        $sizes = Size::pluck('id')->toArray();

        $colorSizeData = [];
        for ($i = 1; $i <= $count; $i++) {
            $colorId = $colors[array_rand($colors)];
            $sizeId = $sizes[array_rand($sizes)];

            $colorSize = [
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'quantity' => rand(1, 10),
            ];

            $colorSizeData[] = $colorSize;
        }

        ColorSize::insert($colorSizeData);
    }

    public function generateImageData($product)
    {
        return [
            'url' => 'image' . $this->generateUniqueCounter() . '.jpg',
            'imageable_id' => $product->id,
            'imageable_type' => Product::class,
        ];
    }

}
