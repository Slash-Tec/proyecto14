<?php

namespace Tests\Unit;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\DropdownCart;
use App\Http\Livewire\ShoppingCart;
use App\Http\Livewire\UpdateCartItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;
use Tests\TestResources;

class CartTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /*public function it_adds_multiple_items_to_cart_and_maintains_cart_after_login()
    {
        $halo = TestResources::createHalo();
        $mobile = TestResources::createMobile();
        $shirt = TestResources::createShirt();

        $sizes = ['S', 'M', 'L'];

        foreach ($sizes as $sizeName) {
            $size = Size::firstOrCreate([
                'name' => $sizeName,
                'product_id' => $shirt->id,
            ]);
            $shirt->sizes()->attach($size->id, ['quantity' => 10]);
        }

        $colors = ['Rojo', 'Azul', 'Verde'];

        foreach ($colors as $colorName) {
            $color = Color::create(['name' => $colorName]);

            DB::table('color_product')->insert([
                'color_id' => $color->id,
                'product_id' => $shirt->id,
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cart::add([
            'id' => $halo->id,
            'name' => $halo->name,
            'qty' => 1,
            'price' => $halo->price,
            'weight' => 550,
        ]);

        Cart::add([
            'id' => $mobile->id,
            'name' => $mobile->name,
            'qty' => 2,
            'price' => $mobile->price,
            'weight' => 550,
            'options' => [
                'color_id' => $mobile->colors->first()->id,
            ],
        ]);

        Cart::add([
            'id' => $shirt->id,
            'name' => $shirt->name,
            'qty' => 1,
            'price' => $shirt->price,
            'weight' => 550,
            'options' => [
                'color_id' => $shirt->colors->first()->id,
                'size_id' => $sizes->first()->id, // Usar la primera talla
            ],
        ]);

        Auth::logout();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->assertTrue(Cart::content()->contains('id', $halo->id));
        $this->assertTrue(Cart::content()->contains('id', $mobile->id));
        $this->assertTrue(Cart::content()->contains('id', $shirt->id));
    }*/

    /** @test */ //Mejorar
    public function products_are_visible_in_minicart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem')
            ->assertEmitted('render');

        $response = $this->get('/');
        $response->assertSee($product->name);
    }

    /** @test */
    public function red_dot_on_minicart_grows_when_an_item_is_added_to_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);
        $product->update(['quantity' => 47]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $response = $this->get("/products/{$product->slug}");

        Livewire::test(AddCartItem::class, ['product' => $product])->call('addItem');

        Livewire::test(DropdownCart::class)->emit('render');

        $response = $this->get("/products/{$product->slug}");
        $response->assertSee('1');
    }

    /** @test */
    public function products_are_visible_in_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem')
            ->assertEmitted('render');

        $this->get('/shopping-cart')
            ->assertSee($product->name)
            ->assertSee('Carrito de compras')
            ->assertSee('Total');
    }

    /** @test */
    public function products_can_change_quantity_in_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem')
            ->assertEmitted('render');

        $this->get('/shopping-cart')
            ->assertSee($product->name)
            ->assertSee(number_format($product->price, 2));

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('increment')
            ->assertEmitted('render');

        $this->get('/shopping-cart')
            ->assertSee(2)
            ->assertSee(number_format($product->price * 2, 2));
    }

    /** @test */
    public function product_can_be_removed_from_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem')
            ->assertEmitted('render');

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name);

        $firstRowId = \Cart::content()->first()->rowId;

        Livewire::test(ShoppingCart::class)
            ->call('delete', $firstRowId)
            ->assertEmitted('render');

        Livewire::test(ShoppingCart::class)
            ->assertDontSee($product->name);
    }

    /** @test */
    public function cart_can_be_dropped()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem')
            ->assertEmitted('render');

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name);

        Livewire::test(ShoppingCart::class)
            ->call('destroy')
            ->assertEmitted('render');

        Livewire::test(ShoppingCart::class)
            ->assertDontSee($product->name);
    }

    /** @test */
    public function it_displays_stock_on_product_page_and_updates_correctly_when_added_to_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $product->update(['quantity' => 15]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->quantity);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem')
            ->assertEmitted('render');

        $responseAfterAddingToCart = $this->get("/products/{$product->slug}");

        $responseAfterAddingToCart->assertSee(14);
    }

    /** @test */
    public function it_displays_stock_with_color_on_product_page_and_updates_correctly_when_added_to_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $this->generateColorData();
        $color = Color::first();

        $this->generateColorProductData(1, $product, $color, ['quantity' => 15]);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee("Color");
        $response->assertSee('15');

        Livewire::test(AddCartItemColor::class, ['product' => $product, 'colors' => [$color]])
            ->set('color_id', $color->id)
            ->call('addItem');

        $responseAfterAddingToCart = $this->get("/products/{$product->slug}");

        $responseAfterAddingToCart->assertSee('14');
    }

    /** @test */
    public function it_displays_stock_with_color_and_size_on_product_page_and_updates_correctly_when_added_to_cart()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $this->generateColorData();
        $color = Color::first();

        $this->generateSizeData($product->id);
        $size = Size::where('product_id', $product->id)->first();

        $this->generateColorSizeData($color, $size, ['quantity' => 10]);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('10');

        Livewire::test(AddCartItemSize::class, ['product' => $product, 'sizes' => [$size]])
            ->set('size_id', $size->id)
            ->set('color_id', $color->id)
            ->call('addItem');

        $responseAfterAddingToCart = $this->get("/products/{$product->slug}");

        $responseAfterAddingToCart->assertSee('9');
    }
}
