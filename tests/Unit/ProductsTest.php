<?php

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_product_details()
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

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->description);
    }
}
