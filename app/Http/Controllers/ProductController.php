<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::active()->latest()->paginate(12);
        return view('pages.products.index', compact('products'));
    }

public function show(Product $product)
{
    // Decode the cart cookie (fallback to empty array)
    $cart = json_decode(request()->cookie('cart', '[]'), true);

    // Check if this product is in the cart and get its quantity
    $cartItem = collect($cart)->firstWhere('id', $product->id);

    // Attach quantity to the product (default to 1 if not found)
    $product->quantity = $cartItem['quantity'] ?? 0;

    // Get related products (excluding the current one)
    $relatedProducts = Product::where('id', '!=', $product->id)
                          ->inRandomOrder()
                          ->take(4)
                          ->latest()->get();

    return view('pages.products.show', compact('product', 'relatedProducts'));
}

}