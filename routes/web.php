<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    $featuredProducts = \App\Models\Product::allFromJson()->take(3);
    return view('pages.home', compact('featuredProducts'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', function () {
    $cart = json_decode(request()->cookie('cart', '[]'), true);
    $products = \App\Models\Product::allFromJson()
        ->filter(function ($product) use ($cart) {
            return in_array($product['id'], array_column($cart, 'id'));
        })
        ->map(function ($product) use ($cart) {
            $cartItem = collect($cart)->firstWhere('id', $product['id']);
            $product['quantity'] = $cartItem['quantity'] ?? 1;
            return $product;
        });
    
    return view('pages.cart', compact('products'));
})->name('cart');

Route::post('/cart/add/{id}', function ($id) {
    $cart = json_decode(request()->cookie('cart', '[]'), true);
    $quantity = request('quantity', 1);
    
    // Find if product already exists in cart
    $found = false;
    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // If not found, add new item
    if (!$found) {
        $cart[] = [
            'id' => $id,
            'quantity' => $quantity
        ];
    }
    
return response()->json([
        'success' => true,
        'message' => 'Product added to cart!',
        'cart' => $cart,
    ])->cookie('cart', json_encode($cart), 60 * 24 * 30);
})->name('cart.add');

Route::delete('/cart/remove/{id}', function ($id) {
    $cart = json_decode(request()->cookie('cart', '[]'), true);
    
    // Filter out the item to remove
    $cart = array_filter($cart, function ($item) use ($id) {
        return $item['id'] != $id;
    });
    
    // Reset array keys
    $cart = array_values($cart);
    
    return back()
        ->with('success', 'Product removed from cart!')
        ->cookie('cart', json_encode($cart), 60*24*30);
})->name('cart.remove');

Route::post('/cart/update/{id}', function ($id) {
    $cart = json_decode(request()->cookie('cart', '[]'), true);
    $quantity = (int)request('quantity', 1);
    
    // If quantity is 0 or less, remove the item
    if ($quantity <= 0) {
        $cart = array_values(array_filter($cart, function ($item) use ($id) {
            return $item['id'] != $id;
        }));
    } else {
        // Update quantity for the item
        foreach ($cart as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
    
    return back()
        ->with('success', 'Cart updated')
        ->cookie('cart', json_encode($cart), 60*24*30);
})->name('cart.update');
Route::get('/checkout', function () {
    $cart = json_decode(request()->cookie('cart', '[]'), true);
    $products = Product::allFromJson()
        ->filter(function ($product) use ($cart) {
            return in_array($product['id'], array_column($cart, 'id'));
        })
        ->map(function ($product) use ($cart) {
            $cartItem = collect($cart)->firstWhere('id', $product['id']);
            $product['quantity'] = $cartItem['quantity'] ?? 1;
            $product['total'] = $product['price'] * $product['quantity'];
            return $product;
        });
    
    $subtotal = $products->sum('total');
    $total = $subtotal + 5.00; // Shipping
    
    return view('pages.checkout', compact('products', 'subtotal', 'total'));
})->name('checkout');

Route::post('/checkout', function () {
    // Validate form data
    $validated = request()->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'zip' => 'required|string',
        'country' => 'required|string',
        'transfer_name' => 'required|string',
        'transfer_amount' => 'required|numeric',
        'transfer_date' => 'required|date',
        'transfer_reference' => 'required|string',
    ]);
    
    // Generate order ID
    $orderId = 'IVY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    
    // Get cart items
    $cart = json_decode(request()->cookie('cart', '[]'), true);
    $products = Product::allFromJson()
        ->filter(function ($product) use ($cart) {
            return in_array($product['id'], array_column($cart, 'id'));
        });
    
    // In a real app, you would:
    // 1. Save the order to database
    // 2. Send confirmation emails
    // 3. Clear the cart
    
    return redirect()->route('checkout.success')
        ->with('order_id', $orderId)
        ->withoutCookie('cart');
})->name('checkout.submit');

Route::get('/checkout/success', function () {
    if (!session()->has('order_id')) {
        return redirect()->route('home');
    }
    
    return view('pages.checkout-success', [
        'orderId' => session('order_id')
    ]);
})->name('checkout.success');