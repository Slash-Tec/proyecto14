<?php

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

}
