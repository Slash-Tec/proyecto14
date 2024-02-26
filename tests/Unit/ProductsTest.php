<?php

use App\Http\Livewire\AddCartItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_product_details()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $product = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'description' => 'Descripción del producto',
            'price' => 19.99,
            'stock' => 10,
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
        ]);

        $product->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);

        $response->assertSee($product->name);
        $response->assertSee($product->description);
        $response->assertSee(number_format($product->price, 2));
        $response->assertSee('tests/example.jpg');
        $response->assertSee('Stock disponible: ' . $product->stock);
        $response->assertSee($product->price);
        $response->assertSee('Agregar al carrito de compras');
        $response->assertSee('+');
        $response->assertSee('-');
    }

    /** @test */
    public function it_checks_maximum_stock_product()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $product = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'description' => 'Descripción del producto',
            'price' => 19.99,
            'stock' => 10,
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
        ]);

        $product->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        for ($i = 1; $i <= $product->stock; $i++) {
            Livewire::test(AddCartItem::class, ['product' => $product])
                ->call('increment')
                ->call('addItem')
                ->assertStatus(200)
                ->assertSet('qty', $i)
                ->assertDontSeeHtml('<button class="btn btn-primary" id="btn-increase-quantity" disabled>+</button>');
        }

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('increment')
            ->call('addItem')
            ->assertDontSeeHtml('<button class="btn btn-primary" id="btn-increase-quantity" disabled>+</button>');
    }

    /** @test */
    public function it_checks_minimum_stock_product()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Sony',
            'slug' => 'sony',
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Square Enix',
            'slug' => 'square',
            'category_id' => $category->id,
        ]);

        $product = Product::create([
            'name' => 'NEO The World Ends With You',
            'slug' => 'neo',
            'description' => 'Descripción del producto',
            'price' => 19.99,
            'stock' => 10,
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
        ]);

        $product->images()->create([
            'url' => 'tests/example.jpg',
        ]);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->set('qty', 1)
            ->assertDontSee('<button class="btn btn-primary" id="btn-decrease-quantity" disabled>-</button>');
    }

    /*public function it_checks_products_with_color*/

}
