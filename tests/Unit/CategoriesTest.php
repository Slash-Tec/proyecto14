<?php

namespace Tests\Unit;

use App\Http\Livewire\CategoryFilter;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
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

        $subcategory = Subcategory::create([
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
            'name' => 'Wii',
            'slug' => 'wii',
        ]);

        $product = Product::create([
            'name' => 'Mario',
            'slug' => 'mario',
            'description' => 'Descripción del producto',
            'subcategory_id' => $subcategory->id,
            'price' => '19.99',
            'brand_id' => $brand->id,
            'status' => '2',
        ]);

        Image::create([
            'url' => 'tests/example.jpg',
            'imageable_id' => $product->id,
            'imageable_type' => 'App\Models\Product',
        ]);

        Livewire::test(CategoryFilter::class, ['category' => $category])
            ->call('limpiar')
            ->assertSee($product->name);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($product->name);
    }

    /** @test */
    public function it_shows_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $brand = Brand::create([
            'name' => 'Wii',
            'slug' => 'wii',
        ]);

        $category->brands()->attach($brand->id);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($brand->name);
    }

    /** @test **/
    public function it_uses_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
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

        $xenoblade = Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'slug' => 'xenoblade',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'brand_id' => $monolith->id,
            'subcategory_id' => $nintendo->id,
        ]);

        $hearts = Product::create([
            'name' => 'Kingdom Hearts',
            'slug' => 'hearts',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'brand_id' => $square->id,
            'subcategory_id' => $sony->id,
        ]);

        $xenoblade->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        $hearts->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        Livewire::test(CategoryFilter::class, ['category' => $category, 'marca' => $square->name])
            ->assertSee($hearts->name)
            ->assertDontSee($xenoblade->name);
    }

    /** @test **/
    public function it_uses_subcategory_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
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

        $xenoblade = Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'slug' => 'xenoblade',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'brand_id' => $monolith->id,
            'subcategory_id' => $nintendo->id,
        ]);

        $hearts = Product::create([
            'name' => 'Kingdom Hearts',
            'slug' => 'hearts',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'brand_id' => $square->id,
            'subcategory_id' => $sony->id,
        ]);

        $xenoblade->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        $hearts->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        Livewire::test(CategoryFilter::class, ['category' => $category, 'subcategory' => $sony->name])
            ->assertSee($hearts->name)
            ->assertDontSee($xenoblade->name);
    }

    /** @test **/
    public function it_uses_subcategory_and_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
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

        $neo = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $nintendo->id,
            'brand_id' => $square->id,
        ]);

        $xenoblade = Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'slug' => 'xenoblade',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $nintendo->id,
            'brand_id' => $monolith->id,
        ]);

        $hearts = Product::create([
            'name' => 'Kingdom Hearts',
            'slug' => 'hearts',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $sony->id,
            'brand_id' => $square->id,
        ]);

        $neo->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        $xenoblade->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        $hearts->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        Livewire::test(CategoryFilter::class, ['category' => $category, 'subcategory' => $sony->name, 'marca' => $square->name])
            ->assertSee($hearts->name)
            ->assertDontSee($xenoblade->name)
            ->assertDontSee($neo->name);
    }
}

