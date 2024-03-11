<?php

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\ColorSize;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase, CreateData;

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
    public function it_checks_products_with_color()
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
            'url' => 'ruta/de/tu/imagen.jpg',
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
        ]);

        $colors = [
            'Rojo',
            'Azul',
            'Verde',
        ];

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

    public function it_checks_products_with_size()
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
            'url' => 'ruta/de/tu/imagen.jpg',
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
        ]);

        $colors = [
            'Rojo',
            'Azul',
            'Verde',
        ];

        foreach ($colors as $colorName) {
            $color = Color::create(['name' => $colorName]);
            $product->colors()->attach($color->id, ['quantity' => 10]);
        }

        $sizes = [
            'S',
            'M',
            'L',
        ];

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

    /** @test */
    public function it_displays_stock_on_product_page()
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

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);

        $response->assertSee($product->name);
        $response->assertSee($product->quantity);
    }

    /** @test */
    public function it_displays_stock_with_color_on_product_page()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $this->generateColorData();
        $color = Color::first();

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $this->generateColorProductData(1, $product, $color);

        $quantity = ColorProduct::where('color_id', $color->id)
            ->where('product_id', $product->id)
            ->value('quantity');

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee($quantity)
            ->assertSee("Color");
    }

    /** @test */
    public function it_displays_stock_with_color_and_size_on_product_page()
    {
        $categoryData = $this->generateCategoryData();
        $category = Category::create($categoryData);

        $brandData = $this->generateBrandData($category);
        $brand = Brand::create($brandData);

        $subcategoryData = $this->generateSubcategoryData($category);
        $subcategory = Subcategory::create($subcategoryData);

        $this->generateColorData();
        $color = Color::first();

        $productData = $this->generateProductData(1, $subcategory, $brand);
        $product = Product::create($productData[0]);

        $sizeData = $this->generateSizeData($product->id);
        $sizeId = $sizeData->id;

        $this->generateColorProductData(1, $product, $color);

        $quantity = ColorSize::where('color_id', $color->id)
            ->where('size_id', $product->sizes->first()->id)
            ->value('quantity');

        $imageData = $this->generateImageData($product);
        Image::create($imageData);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee($quantity);
    }

}

