<?php

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
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


    /** @test */
    public function it_checks_products_with_color($colors = [])
    {
        $category = Category::create([
            'name' => 'Ropa',
            'slug' => 'ropa',
            'icon' => '<i class="fas fa-tshirt"></i>',
            'image' => 'tests/example.jpg',
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Camisetas',
            'slug' => 'camisetas',
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Zara',
            'slug' => 'zara',
            'category_id' => $category->id,
        ]);

        $product = Product::create([
            'name' => 'Camiseta de algodón',
            'slug' => 'camiseta-algodon',
            'description' => 'Camiseta cómoda de algodón',
            'price' => 29.99,
            'stock' => 30,
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
        ]);

        Image::create([
            'url' => 'tests/example.jpg',
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
        ]);

        foreach ($colors as $colorName) {
            $color = Color::create(['name' => $colorName]);
            $product->colors()->attach($color->id, ['quantity' => 10]);
        }

        $availableColors = Color::all();

        Livewire::test(AddCartItemColor::class, ['product' => $product, 'colors' => $availableColors])
            ->set('color_id', $availableColors[0]->id)
            ->assertSee($availableColors[0]->name)
            ->assertSee($availableColors[1]->name)
            ->assertSee($availableColors[2]->name);
    }

    public function it_checks_products_with_size($colors = [], $sizes = [])
    {
        $category = Category::create([
            'name' => 'Ropa',
            'slug' => 'ropa',
            'icon' => '<i class="fas fa-tshirt"></i>',
            'image' => 'tests/example.jpg',
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Camisetas',
            'slug' => 'camisetas',
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Zara',
            'slug' => 'zara',
            'category_id' => $category->id,
        ]);

        $product = Product::create([
            'name' => 'Camiseta de algodón',
            'slug' => 'camiseta-algodon',
            'description' => 'Camiseta cómoda de algodón',
            'price' => 29.99,
            'stock' => 30,
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
        ]);

        Image::create([
            'url' => 'tests/example.jpg',
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
        ]);

        foreach ($colors as $colorName) {
            $color = Color::create(['name' => $colorName]);
            $product->colors()->attach($color->id, ['quantity' => 10]);
        }

        foreach ($sizes as $sizeName) {
            $size = Size::create(['name' => $sizeName]);
            $product->sizes()->attach($size->id, ['quantity' => 10]);
        }

        $availableColors = Color::all();
        $availableSizes = Size::all();

        Livewire::test(AddCartItemColor::class, ['product' => $product, 'colors' => $availableColors])
            ->set('color_id', $availableColors[0]->id)
            ->call('updatedColorId', $availableColors[0]->id)
            ->set('size_id', $availableSizes[0]->id)
            ->call('addItem')
            ->assertEmitted('dropdown-cart', 'render')
            ->assertSee($availableColors[0]->name)
            ->assertSee($availableSizes[0]->name);
    }

}
