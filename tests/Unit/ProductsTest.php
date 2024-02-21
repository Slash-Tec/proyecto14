<?php

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
        $response->assertSee('<img src="' . asset('storage/' . $product->image) . '" alt="' . $product->name . '">');
        $response->assertSee('Stock disponible: ' . $product->stock);
        $response->assertSee('<button id="btn-add-to-cart">Agregar al carrito de compras</button>');
        $response->assertSee('<button id="btn-increase-quantity">+</button>');
        $response->assertSee('<button id="btn-decrease-quantity">-</button>');
    }

    /** @test */
    public function it_checks_maximum_stock_product()
    {
        $product = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'price' => 19.99,
            'stock' => 10,
        ]);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);

        $response->assertSee('<span class="mx-2 text-gray-700">' . $product->stock . '</span>');
        $response->assertDontSee('<button class="btn btn-primary" id="btn-increase-quantity">+</button>');
        $response->assertSee('<button class="btn btn-primary" id="btn-increase-quantity" disabled>+</button>');
    }

    /** @test */
    public function it_checks_minimum_stock_product()
    {
        $product = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'price' => 19.99,
            'stock' => 10,
        ]);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);

        $response->assertSee('<span class="mx-2 text-gray-700">1</span>');
        $response->assertDontSee('<button class="btn btn-danger" id="btn-decrease-quantity">-</button>');
        $response->assertSee('<button class="btn btn-danger" id="btn-decrease-quantity" disabled>-</button>');
    }
}
