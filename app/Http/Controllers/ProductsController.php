<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function files(Product $product, Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $url = $request->file('file')->store('products', 'public');

        $product->images()->create([
            'url' => $url
        ]);
    }
}
