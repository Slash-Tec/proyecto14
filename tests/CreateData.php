<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\ColorSize;
use App\Models\Department;
use App\Models\District;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use Database\Seeders\ColorProductSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SizeSeeder;
use Illuminate\Database\Query\Builder;

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

    public function generateColorData()
    {
        $colors = ['Amarillo', 'Azul', 'Verde', 'Rojo'];

        $colorData = [];
        foreach ($colors as $colorName) {
            $color = ['name' => $colorName];
            $colorData[] = $color;
        }

        Color::insert($colorData);
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
                'quantity' => 15,
                'sold' => rand(0, 30),
                'reserved' => rand(0, 10),
                'status' => 2,
            ];
        }

        return $products;
    }

    public function generateColorProductData($count, $product, $color)
    {
        $colorProductData = [];

        for ($i = 1; $i <= $count; $i++) {
            $colorProductData[] = [
                'color_id' => $color->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 10),
            ];
        }

        ColorProduct::insert($colorProductData);
    }

    public function generateSizeData($productId)
    {
        return Size::create([
            'name' => 'Size' . $this->generateUniqueCounter(),
            'product_id' => $productId,
        ]);
    }

    public function generateColorSizeData()
    {
        $colors = Color::pluck('id')->toArray();

        if (empty($colors)) {
            return;
        }

        $sizeIds = Size::pluck('id')->toArray();

        $colorSizeData = [];

        foreach ($colors as $colorId) {
            foreach ($sizeIds as $sizeId) {
                $colorSize = [
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'quantity' => rand(1, 10),
                ];

                $colorSizeData[] = $colorSize;
            }
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

    public function generateDepartmentData()
    {
        return [
            'name' => 'Departamento' . $this->generateUniqueCounter(),
        ];
    }

    public function generateCityData($department)
    {
        return [
            'name' => 'Ciudad' . $this->generateUniqueCounter(),
            'cost' => rand(10, 100) / 10,
            'department_id' => $department->id,
        ];
    }

    public function generateDistrictData($city)
    {
        return [
            'name' => 'Distrito' . $this->generateUniqueCounter(),
            'city_id' => $city->id,
        ];
    }

}
