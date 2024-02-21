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
        $product = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'image' => '../example.jpg',
            'description' => 'Descripción del producto',
            'price' => 19.99,
            'stock' => 10,
        ]);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);

        $response->assertSee($product->name);
        $response->assertSee($product->description);
        $response->assertSee(number_format($product->price, 2));
        $response->assertSee('Stock disponible: ' . $product->stock);
        $response->assertSee('<button id="btn-add-to-cart">Agregar al carrito de compras</button>');
        $response->assertSee('<button id="btn-increase-quantity">+</button>');
        $response->assertSee('<button id="btn-decrease-quantity">-</button>');
    }
}
