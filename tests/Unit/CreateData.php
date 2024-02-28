<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\ColorSize;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use BrandCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateData
{
    public function generateCategoryData($count)
    {
        $categories = [];
        for ($i = 1; $i <= $count; $i++) {
            $category = [
                'name' => 'Category' . $i,
                'slug' => 'category-' . $i,
                'icon' => 'icon' . $i . '.png',
                'image' => 'image' . $i . '.jpg',
            ];
            $categories[] = $category;
        }

        Category::insert($categories);
    }

    public function generateBrandData($count)
    {
        $brands = [];
        for ($i = 1; $i <= $count; $i++) {
            $brand = [
                'name' => 'Brand' . $i,
            ];
            $brands[] = $brand;
        }

        Brand::insert($brands);
    }

    public function generateBrandCategoryData($count)
    {
        $brandIds = Brand::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        $brandCategoryData = [];
        for ($i = 1; $i <= $count; $i++) {
            $brandId = $brandIds[array_rand($brandIds)];
            $categoryId = $categoryIds[array_rand($categoryIds)];

            $brandCategory = [
                'brand_id' => $brandId,
                'category_id' => $categoryId,
            ];

            $brandCategoryData[] = $brandCategory;
        }

        BrandCategory::insert($brandCategoryData);
    }

    public function generateSubcategoryData($count)
    {
        $categories = Category::pluck('id')->toArray();

        $subcategories = [];
        for ($i = 1; $i <= $count; $i++) {
            $subcategory = [
                'name' => 'Subcategory' . $i,
                'slug' => 'subcategory-' . $i,
                'color' => rand(0, 1),
                'size' => rand(0, 1),
                'category_id' => $categories[array_rand($categories)],
            ];
            $subcategories[] = $subcategory;
        }

        Subcategory::insert($subcategories);
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

    public function generateProductData($count, $subcategory, $brand)
    {
        $products = [];
        for ($i = 1; $i <= $count; $i++) {
            $product = [
                'name' => 'Product' . $i,
                'slug' => 'product-' . $i,
                'description' => 'Description for Product' . $i,
                'price' => rand(10, 100),
                'subcategory_id' => $subcategory->id,
                'brand_id' => $brand->id,
                'quantity' => rand(1, 50),
                'sold' => rand(0, 30),
                'reserved' => rand(0, 10),
                'status' => rand(1, 2),
            ];
            $products[] = $product;
        }

        Product::insert($products);

        return Product::first();
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

    public function generateImageData($count)
    {
        $imageableTypes = ['Product', 'Category', 'Brand'];
        $images = [];

        for ($i = 1; $i <= $count; $i++) {
            $imageableType = $imageableTypes[array_rand($imageableTypes)];

            $image = [
                'url' => 'https://example.com/image' . $i . '.jpg',
                'imageable_id' => rand(1, 100),
                'imageable_type' => $imageableType,
            ];

            $images[] = $image;
        }

        Image::insert($images);
    }

}
