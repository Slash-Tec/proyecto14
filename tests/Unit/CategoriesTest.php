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

    /** @test **/
    public function it_uses_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $monolith = Brand::create([
            'name' => 'Monolith Soft',
            'slug' => 'monolith',
            'category_id' => $category->id,
        ]);

        $square = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $xenoblade = Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'brand_id' => $monolith->id,
        ]);

        $hearts = Product::create([
            'name' => 'Kingdom Hearts',
            'brand_id' => $square->id,
        ]);

        $response = $this->get('/categories/' . $category->slug . '?brand=' . $square->slug);
        $response->assertStatus(200);
        $response->assertSee($hearts->name);
        $response->assertDontSee($xenoblade->name);
    }

    /** @test **/
    public function it_uses_subcategory_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $sony = Subcategory::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'category_id' => $category->id,
        ]);

        $nintendo = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'category_id' => $category->id,
        ]);

        $godofwar = Product::create([
            'name' => 'God of war',
            'subcategory_id' => $sony->id,
        ]);

        $zelda = Product::create([
            'name' => 'Zelda',
            'subcategory_id' => $nintendo->id,
        ]);

        $response = $this->get('/categories/' . $category->slug . '?subcategory=' . $sony->slug);
        $response->assertStatus(200);
        $response->assertSee($godofwar->name);
        $response->assertDontSee($zelda->name);
    }

    /** @test **/
    public function it_uses_subcategory_and_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $sony = Subcategory::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'category_id' => $category->id,
        ]);

        $nintendo = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'category_id' => $category->id,
        ]);

        $monolith = Brand::create([
            'name' => 'Monolith Soft',
            'slug' => 'monolith',
            'category_id' => $category->id,
        ]);

        $square = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $godofwar = Product::create([
            'name' => 'God of war',
            'subcategory_id' => $sony->id,
        ]);

        $neo = Product::create([
            'name' => 'NEO The World Ends With You',
            'brand_id' => $square->id,
        ]);

        $xenoblade = Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'subcategory_id' => $nintendo->id,
            'brand_id' => $monolith->id,
        ]);

        $hearts = Product::create([
            'name' => 'Kingdom Hearts',
            'subcategory_id' => $sony->id,
            'brand_id' => $square->id,
        ]);

        $response = $this->get('/categories/' . $category->slug . '?subcategory=' . $sony->slug . '?brand=' . $square->slug);
        $response->assertStatus(200);
        $response->assertSee($hearts->name);
        $response->assertDontSee($neo->name);
        $response->assertDontSee($godofwar->name);
        $response->assertDontSee($xenoblade->name);
    }
}

