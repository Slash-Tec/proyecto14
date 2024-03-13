<?php

use App\Http\Livewire\Admin\CreateProduct;
use App\Http\Livewire\Admin\EditProduct;
use App\Http\Livewire\Search;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Tests\CreateData;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Subcategory;
use Livewire\Livewire;

class AdminTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function admin_can_search_and_view_results()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $genericProducts = [];
        $lastProduct = null;

        for ($i = 0; $i < 11; $i++) {
            $productData = $this->generateProductData(1, $subcategory, $brand);
            $product = Product::create($productData[0]);

            $imageData = $this->generateImageData($product);
            Image::create($imageData);

            $genericProducts[] = $product;

            $lastProduct = $product;
        }

        if ($lastProduct !== null) {
            $lastProduct->update(['name' => 'ZZZZ']);
        }

        $adminRole = Role::create(['name' => 'admin']);

        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $this->actingAs($adminUser);

        $response = $this->get('/admin');

        Livewire::test(Search::class)
            ->set('search', 'ZZ')
            ->call('render')
            ->assertSee('ZZZZ');
    }

    public function admin_can_edit_product()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');
        $this->actingAs($adminUser);

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

        $editUrl = '/admin/products/' . $product->slug . '/edit';

        $response = $this->get($editUrl);
        $response->assertStatus(200);

        Livewire::test(EditProduct::class, ['product' => $product])
            ->set('name', 'Ragnarok')
            ->set('description', 'Antigua Descripcion')
            ->set('price', 19.99)
            ->call('save')
            ->assertRedirect($editUrl);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Exodia',
            'description' => 'Nueva Descripcion',
            'price' => 29.99,
        ]);

        $adminPageResponse = $this->get('/admin');
        $adminPageResponse->assertSee('Exodia');
    }

    /** @test */
    public function admin_can_create_product()
    {
        $adminRole = Role::create(['name' => 'admin']);

        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $this->actingAs($adminUser);

        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $response = $this->get('/admin/products/create');

        $response->assertStatus(200);

        $response = Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('subcategory_id', $subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('name', 'Ragnarok')
            ->set('description', 'Descripcion del producto')
            ->set('price', 10.99)
            ->call('save');
            $response->assertRedirect('/admin/products/ragnarok/edit');

        $this->assertDatabaseHas('products', [
            'name' => 'Ragnarok',
            'description' => 'Descripcion del producto',
            'price' => 10.99,
        ]);

        $response = $this->get('/admin');

        $response->assertStatus(200);

        $response->assertSee('Ragnarok');
    }
}
