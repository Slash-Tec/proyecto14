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

    /** @test */
    public function users_cannot_access_orders_of_other_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::create([
            'user_id' => $user1->id,
            'contact' => 'Pepe',
            'phone' => '123456789',
            'shipping_cost' => 0.00,
            'total' => 99.99,
            'content' => json_encode([
                '72e0baecb4ab28c9f4bde7b9ec28bfa2' => [
                    'id' => 42,
                    'qty' => 1,
                    'tax' => 21,
                    'name' => 'Optio dolor odio.',
                    'price' => 99.99,
                    'rowId' => '72e0baecb4ab28c9f4bde7b9ec28bfa2',
                    'weight' => 550,
                    'options' => [
                        'image' => '/storage/products/bd44a5365422a7c6ec306eabfe7ece95.jpg',
                        'size_id' => null,
                        'color_id' => null,
                    ],
                    'discount' => 0,
                    'subtotal' => 99.99,
                ]
            ])
        ]);

        $this->actingAs($user2);

        $response = $this->get(route('orders.show', $order));

        $response->assertForbidden();
    }
}
