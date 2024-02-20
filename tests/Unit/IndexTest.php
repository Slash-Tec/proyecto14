<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_products_on_index()
    {
        $category = Category::create([
            'name' => 'Electrónicos',
            'slug' => 'electronicos',
        ]);

        $products = factory(Product::class, 5)->create([
            'category_id' => $category->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($category->name);

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }

    }
}
