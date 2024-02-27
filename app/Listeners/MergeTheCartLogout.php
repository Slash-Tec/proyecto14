<?php

namespace App\Listeners;

use App\Models\Product;
use Illuminate\Auth\Events\Logout;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeTheCartLogout
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
     * @param  \App\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $user = auth()->user();

        $cartContent = Cart::content();

        foreach ($cartContent as $cartItem) {
            $product = Product::find($cartItem->id);

            if ($product) {
                $product->increaseReserved($cartItem->qty); // Utiliza la función para incrementar
            }
        }

        Cart::erase($user->id);
        Cart::store($user->id);
    }
}
