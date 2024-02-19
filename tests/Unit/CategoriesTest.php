<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_category()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $response = $this->get('/categories/' . $category->slug);

        $response->assertStatus(200);

        $response->assertSee($category->name);
    }

    /** @test */
    public function it_shows_subcategory_filters_for_nintendo()
    {
        $category = Category::create([
            'name' => 'Consola y videojuegos',
            'slug' => 'consola-y-videojuegos',
            'icon' => '<i class="fas fa-gamepad"></i>',
        ]);

        $subcategoryNintendo = Subcategory::create([
            'name' => 'Nintendo',
            'slug' => 'nintendo',
            'color' => 0,
            'size' => 0,
            'category_id' => $category->id,
        ]);

        $response = $this->get('/categories/' . $category->slug . '?subcategory=' . $subcategoryNintendo->slug);

        $response->assertStatus(200);

        $response->assertSee($subcategoryNintendo->name);
    }
}

