<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::allFromJson();
        return view('pages.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findFromJson($id);
        $relatedProducts = Product::allFromJson()
            ->where('id', '!=', $id)
            ->take(4);
            
        return view('pages.products.show', compact('product', 'relatedProducts'));
    }
}