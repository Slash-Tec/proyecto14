<?php

namespace Tests\Unit;

use App\Http\Livewire\AddCartItem;
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

    /** @test */
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

    /** @test */
    public function products_are_added_to_cart()
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
}
