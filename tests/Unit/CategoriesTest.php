<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_category()
    {
        $category = Category::factory()->create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);

        $response->assertSee($category->name);
    }

    /** @test */
    public function it_shows_subcategory_filters()
    {
        $category = Category::factory()->create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $subcategory = Subcategory::factory()->create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'color' => 0,
            'size' => 0,
            'category_id' => $category->id,
        ]);

        $response = $this->get('/categories/' . $category->slug . '?subcategory=' . $subcategory->slug);

        $response->assertStatus(200);

        $response->assertSee($subcategory->name);
    }

    /** @test */
    public function it_shows_a_product_on_category_page()
    {
        $category = Category::factory()->create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $subcategory = Subcategory::factory()->create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'color' => 0,
            'size' => 0,
            'category_id' => $category->id,
        ]);

        $product = Product::factory()->create([
            'name' => 'Mario',
            'slug' => 'Mario',
            'description' => 'Descripción del producto',
            'subcategory_id' => $subcategory->id,
            'status' => '2',
        ]);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);

        $response->assertSee($category->name);
    }

    /** @test */
    public function it_shows_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $brand = Brand::create(['name' => 'Monolith Soft']);

        BrandCategory::create(['brand_id' => $brand->id, 'category_id' => $category->id]);

        $response = $this->get('/categories/' . $category->slug);
        $response->assertStatus(200);
        $response->assertSee($brand->name);
    }

    /*public function it_uses_subcategory_filters()*/
    /*public function it_uses_brand_filters()*/
    /*public function it_uses_subcategory_and_brand_filters()*/
}

