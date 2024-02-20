<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_index_products()
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

        $products = Product::factory(5)->create([
            'subcategory_id' => $subcategory->id,
            'status' => '1',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($category->name);

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    /** @test */
    public function it_hides_products_with_status_1()
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

        Product::factory(5)->create([
            'subcategory_id' => $subcategory->id,
            'status' => '1',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertDontSeeText('status=1');
    }
}
