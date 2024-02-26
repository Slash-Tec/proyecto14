<?php

use App\Http\Livewire\Navigation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_five_navigation_products()
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

        for ($i = 1; $i <= 5; $i++) {
            Product::create([
                'name' => 'Producto ' . $i,
                'slug' => 'producto-' . $i,
                'description' => 'Descripción del producto ' . $i,
                'subcategory_id' => $subcategory->id,
                'brand_id' => $brand->id,
                'price' => 19.99,
                'status' => '2',
            ]);
        }

        $response = $this->get(route('categories.show', $category));

        $response->assertSee($category->name);

        for ($i = 1; $i <= 5; $i++) {
            $response->assertSee('Producto ' . $i);
        }
    }

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
}
