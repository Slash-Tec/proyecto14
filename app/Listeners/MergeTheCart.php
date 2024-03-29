<?php

namespace App\Listeners;

use App\Models\Product;
use Illuminate\Auth\Events\Login;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeTheCart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = auth()->user();

        $cartContent = Cart::content();

        foreach ($cartContent as $cartItem) {
            $product = Product::find($cartItem->id);

            if ($product) {
                $product->increaseReserved($cartItem->qty);
            }
        }

        Cart::merge($user->id);
    }
}
