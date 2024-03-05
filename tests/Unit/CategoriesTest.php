<?php

namespace Tests\Unit;

use App\Http\Livewire\CategoryFilter;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;
use Tests\TestResources;

class CategoriesTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function it_shows_a_category()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /** @test */
    public function it_shows_subcategory_filters()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $response = $this->get('/categories/' . $category->slug . '?subcategoria=' . $subcategory->slug);

        $response->assertStatus(200);
        $response->assertSee($subcategory->name);
    }

    /** @test */
    public function it_shows_a_product_on_category_page()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($product->name);
    }

    /** @test */
    public function it_shows_brand_filters()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        DB::table('brand_category')->insert([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response = $this->get('/categories/' . $category->slug . '?marca=' . substr($brand->name, 0, 3));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($brand->name);
    }

    /** @test **/
    public function it_uses_brand_filters()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $brand1 = Brand::create($this->generateBrandData($category));
        $brand2 = Brand::create($this->generateBrandData($category));

        DB::table('brand_category')->insert([
            'brand_id' => $brand1->id,
            'category_id' => $category->id,
        ]);

        DB::table('brand_category')->insert([
            'brand_id' => $brand2->id,
            'category_id' => $category->id,
        ]);

        $product1 = Product::create($this->generateProductData(1, $subcategory, $brand1)[0]);
        $product2 = Product::create($this->generateProductData(1, $subcategory, $brand2)[0]);

        Image::create($this->generateImageData($product1));
        Image::create($this->generateImageData($product2));

        $response = $this->get('/categories/' . $category->slug . '?marca=' . urlencode($brand1->name));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($product1->name);
        $response->assertDontSee($product2->name);
    }

    /** @test **/
    public function it_uses_subcategory_filters()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategory1Data = $this->generateSubcategoryData($category);
        $subcategory1 = Subcategory::create($subcategory1Data);

        $subcategory2Data = $this->generateSubcategoryData($category);
        $subcategory2 = Subcategory::create($subcategory2Data);

        $brand = Brand::create($this->generateBrandData($category));

        $product1Data = $this->generateProductData(1, $subcategory1, $brand)[0];
        $product1 = Product::create($product1Data);
        Image::create($this->generateImageData($product1));

        $product2Data = $this->generateProductData(1, $subcategory2, $brand)[0];
        $product2 = Product::create($product2Data);
        Image::create($this->generateImageData($product2));

        $response = $this->get('/categories/' . $category->slug . '?subcategoria=' . urlencode($subcategory1->slug));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($product1->name);
        $response->assertDontSee($product2->name);
    }

    /** @test **/
    public function it_uses_subcategory_and_brand_filters()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategory1Data = $this->generateSubcategoryData($category);
        $subcategory1 = Subcategory::create($subcategory1Data);

        $subcategory2Data = $this->generateSubcategoryData($category);
        $subcategory2 = Subcategory::create($subcategory2Data);

        $brand1 = Brand::create($this->generateBrandData($category));
        $brand2 = Brand::create($this->generateBrandData($category));

        $product1Data = $this->generateProductData(1, $subcategory1, $brand1)[0];
        $product1 = Product::create($product1Data);
        Image::create($this->generateImageData($product1));

        $product2Data = $this->generateProductData(1, $subcategory1, $brand2)[0];
        $product2 = Product::create($product2Data);
        Image::create($this->generateImageData($product2));

        $product3Data = $this->generateProductData(1, $subcategory2, $brand1)[0];
        $product3 = Product::create($product3Data);
        Image::create($this->generateImageData($product3));

        $response = $this->get('/categories/' . $category->slug . '?subcategoria=' . urlencode($subcategory1->slug) . '&marca=' . urlencode($brand1->name));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($product1->name);
        $response->assertDontSee($product2->name);
        $response->assertDontSee($product3->name);
    }

}

