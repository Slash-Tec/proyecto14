<?php

namespace Tests\Unit;

use App\Http\Livewire\CategoryFilter;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\TestResources;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_category()
    {
        $createData = new CreateData();
        $createData->generateCategoryData(1);

        $category = Category::first();

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);

        $response->assertSee($category->name);
    }

    /** @test */
    public function it_shows_subcategory_filters()
    {
        $createData = new CreateData();
        $createData->generateCategoryData(1);
        $createData->generateSubcategoryData(1);

        $category = Category::first();
        $subcategory = Subcategory::first();

        $response = $this->get('/categories/' . $category->slug . '?subcategory=' . $subcategory->slug);

        $response->assertStatus(200);

        $response->assertSee($subcategory->name);
    }

    /** @test */
    public function it_shows_a_product_on_category_page()
    {
        $twewy = TestResources::createTwewy();

        Livewire::test(CategoryFilter::class, ['category' => $twewy->subcategory->category])
            ->call('limpiar')
            ->assertSee('The World Ends');
    }

    /** @test */
    /*public function it_shows_a_product_on_category_page()
    {
        $createData = new CreateData();
        $createData->generateCategoryData(1);
        $createData->generateSubcategoryData(1);

        $category = Category::first();
        $subcategory = Subcategory::first();

        $brandData = $createData->generateBrandData($category);
        $brand = Brand::create($brandData);

        $product = $createData->generateProductData(1, $subcategory, $brand);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);

        $response->assertSee($category->name);
        $response->assertSee($product->name);
    }*/

    /** @test */
    public function it_shows_brand_filters()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
            'image' => 'tests/example.jpg'
        ]);

        $brand = Brand::create([
            'name' => 'Wii',
            'slug' => 'wii',
        ]);

        $category->brands()->attach($brand->id);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee($brand->name);
    }

    /** @test **/
    public function it_uses_brand_filters()
    {
        $xenoblade = TestResources::createXenoblade();
        $hearts = TestResources::createHearts();

        Livewire::test(CategoryFilter::class, ['category' => $hearts->subcategory->category, 'marca' => 'Square Enix'])
            ->assertSee('Kingdom Hearts')
            ->assertDontSee('Xenoblade Chronicles');
    }

    /** @test **/
    public function it_uses_subcategory_filters()
    {
        $xenoblade = TestResources::createXenoblade();
        $hearts = TestResources::createHearts();

        Livewire::test(CategoryFilter::class, ['category' => $hearts->subcategory->category, 'subcategory' => 'Sony'])
            ->assertSee('Kingdom Hearts')
            ->assertDontSee('Xenoblade Chronicles');
    }

    /** @test **/
    public function it_uses_subcategory_and_brand_filters()
    {
        $xenoblade = TestResources::createXenoblade();
        $hearts = TestResources::createHearts();
        Livewire::test(CategoryFilter::class, ['category' => $hearts->subcategory->category, 'subcategory' => 'Sony', 'marca' => 'Square Enix'])
            ->assertSee('Kingdom Hearts')
            ->assertDontSee('Xenoblade Chronicles');
    }
}

