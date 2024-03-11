<?php

use App\Http\Livewire\Navigation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\CreateData;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase, CreateData, WithFaker;

    /** @test */
    /*public function it_shows_five_navigation_products()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $product1 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product2 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product3 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product4 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);
        $product5 = Product::create($this->generateProductData(1, $subcategory, $brand)[0]);

        $response = $this->get('/');*/

        /*dd($product1->name, $product2->name, $product3->name, $product4->name, $product5->name);*/

        /*$response->assertViewHas('Producto 8', function ($product1){
            return $product1->contains($product1);
        });
        $response->assertSee($product2->name);
        $response->assertSee($product3->name);
        $response->assertSee($product4->name);
        $response->assertSee($product5->name);
    }*/

    /** @test */
    public function it_hides_products_with_status_1()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $subcategory = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'color' => 0,
            'size' => 0,
            'category_id' => $category->id,
        ]);

        $brand = Brand::create([
            'name' => 'Monolith Soft',
            'slug' => 'monolith',
            'category_id' => $category->id,
        ]);

        Product::create([
            'name' => 'Xenoblade Chronicles 3',
            'slug' => 'xenoblade',
            'description' => 'Descripción del producto',
            'price' => '19.99',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'status' => '1',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Xenoblade Chronicles 3');
    }

    /** @test */
    public function it_redirects_guest_users_to_login_from_admin_content()
    {
        $routes = [
            '/admin',
            '/admin/orders',
            '/admin/categories',
            '/admin/brands',
            '/admin/departments',
            '/admin/users',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    /** @test */
    public function it_redirects_guest_users_to_login_from_orders_create()
    {
        $response = $this->get('/orders/create');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_has_access_to_orders_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/orders/create');

        $response->assertSee("Nombre de contacto");
    }

    /** @test */
    public function admin_can_access_admin_orders()
    {
        $adminRole = Role::create(['name' => 'admin']);

        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $this->actingAs($adminUser);

        $response = $this->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertSee('Pedidos');
    }
}
