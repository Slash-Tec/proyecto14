<?php

namespace Tests\Unit;

use App\Models\Color;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\TestResources;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_multiple_items_to_cart()
    {

        $halo = TestResources::createHalo();
        $mobile = TestResources::createMobile();
        $shirt = TestResources::createShirt();

        $shirtId = $shirt->id;
        $tabletId = $mobile->id;

        $sizes = ['S', 'M', 'L'];

        foreach ($sizes as $sizeName) {
            Size::create([
                'name' => $sizeName,
                'product_id' => $shirtId,
            ]);
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
                'size_id' => $shirt->sizes->first()->id,
            ],
        ]);

        $this->assertTrue(Cart::content()->contains('id', $halo->id));
        $this->assertTrue(Cart::content()->contains('id', $mobile->id));
        $this->assertTrue(Cart::content()->contains('id', $shirt->id));
    }
}
