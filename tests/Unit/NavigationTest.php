<?php

use App\Http\Livewire\Navigation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    /*public function it_shows_five_navigation_products()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $product1 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product2 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product3 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product4 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product5 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);

        $response = $this->get('/');*/

        /*dd($product1->name, $product2->name, $product3->name, $product4->name, $product5->name);*/

        /*$response->assertViewHas('Producto 8', function ($product1){
            return $product1->contains($product1);
        });
        $response->assertSee($product2->name);
        $response->assertSee($product3->name);
        $response->assertSee($product4->name);
        $response->assertSee($product5->name);
    }*/

    /** @test */
    public function it_hides_products_with_status_1()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'color' => 0,
            'size' => 0,
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Monolith Soft',
            'slug' => 'monolith',
            'category_id' => $category->id,
        ]);

        Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'slug' => 'xenoblade',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'status' => '1',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Xenoblade Chronicles 3');
    }
}
