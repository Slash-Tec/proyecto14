<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function authenticated_user_can_access_order_create_page()
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

        $response = $this->get('/orders/create');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /** @test */
    public function guest_is_redirected_to_login_page_when_accessing_order_create_page()
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

        $response = $this->get('/orders/create');
        $response->assertRedirect('/login');
    }
}
