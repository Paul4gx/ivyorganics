<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CurrencyController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    $featuredProducts = Product::active()->latest()->take(3)->get();
    // dd($featuredProducts);
    return view('pages.home', compact('featuredProducts'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Currency routes
Route::post('/currency/switch', [CurrencyController::class, 'switch'])->name('currency.switch');
Route::post('/currency/switch-ajax', [CurrencyController::class, 'switchAjax'])->name('currency.switch.ajax');
Route::get('/currency/current', [CurrencyController::class, 'getCurrentCurrency'])->name('currency.current');
Route::get('/currency/debug', function () {
    $currencyService = app(\App\Services\CurrencyService::class);
    $request = request();
    
    return response()->json([
        'ip' => $request->ip(),
        'detected_currency' => $currencyService->detectCurrencyFromIP($request),
        'current_currency' => $currencyService->getCurrentCurrency($request),
        'cookie_currency' => $request->cookie('selected_currency'),
        'exchange_rate' => $currencyService->getRealTimeExchangeRate('NGN', 'USD'),
        'sample_price_ngn' => $currencyService->formatPrice(1000, 'NGN'),
        'sample_price_usd' => $currencyService->formatPrice(1000, 'USD'),
    ]);
})->name('currency.debug');

Route::get('/currency/clear-cache', [CurrencyController::class, 'clearCache'])->name('currency.clear-cache');

Route::get('/cart', function () {
    // Get the cart from cookie
    $cart = json_decode(request()->cookie('cart', '[]'), true);

    // Extract product IDs
    $productIds = array_column($cart, 'id');

    // Fetch real products from the DB
    $products = Product::whereIn('id', $productIds)->get()->map(function ($product) use ($cart) {
        // Get quantity from cart by matching ID
        $cartItem = collect($cart)->firstWhere('id', $product->id);
        $product->quantity = $cartItem['quantity'] ?? 1;
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
// Route::get('/checkout', function () {
//     $cart = json_decode(request()->cookie('cart', '[]'), true);
//     $products = Product::allFromJson()
//         ->filter(function ($product) use ($cart) {
//             return in_array($product['id'], array_column($cart, 'id'));
//         })
//         ->map(function ($product) use ($cart) {
//             $cartItem = collect($cart)->firstWhere('id', $product['id']);
//             $product['quantity'] = $cartItem['quantity'] ?? 1;
//             $product['total'] = $product['price'] * $product['quantity'];
//             return $product;
//         });
    
//     $subtotal = $products->sum('total');
//     $total = $subtotal + 5.00; // Shipping
    
//     return view('pages.checkout', compact('products', 'subtotal', 'total'));
// })->name('checkout');

// Route::post('/checkout', function () {
//     // Validate form data
//     $validated = request()->validate([
//         'first_name' => 'required|string|max:255',
//         'last_name' => 'required|string|max:255',
//         'email' => 'required|email',
//         'phone' => 'required|string',
//         'address' => 'required|string',
//         'city' => 'required|string',
//         'state' => 'required|string',
//         'zip' => 'required|string',
//         'country' => 'required|string',
//         'transfer_name' => 'required|string',
//         'transfer_amount' => 'required|numeric',
//         'transfer_date' => 'required|date',
//         'transfer_reference' => 'required|string',
//     ]);
    
//     // Generate order ID
//     $orderId = 'IVY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    
//     // Get cart items
//     $cart = json_decode(request()->cookie('cart', '[]'), true);
//     $products = Product::allFromJson()
//         ->filter(function ($product) use ($cart) {
//             return in_array($product['id'], array_column($cart, 'id'));
//         });    
//     return redirect()->route('checkout.success')
//         ->with('order_id', $orderId)
//         ->withoutCookie('cart');
// })->name('checkout.submit');
    
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.submit');
Route::get('/checkout/verify', [CheckoutController::class, 'verify'])->name('checkout.verify');
Route::get('/checkout/success', function () {
    if (!session()->has('order_id')) {
        return redirect()->route('home');
    }
    
    return view('pages.checkout-success', [
        'orderId' => session('order_id')
    ]);
})->name('checkout.success');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // Orders
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    
    // Shipping
    Route::get('shipping', [App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::get('shipping/countries/{country}/states', [App\Http\Controllers\Admin\ShippingController::class, 'showStates'])->name('shipping.countries.states.view');
    
    // Country routes
    Route::post('shipping/countries', [App\Http\Controllers\Admin\ShippingController::class, 'storeCountry'])->name('shipping.countries.store');
    Route::put('shipping/countries/{country}', [App\Http\Controllers\Admin\ShippingController::class, 'updateCountry'])->name('shipping.countries.update');
    Route::delete('shipping/countries/{country}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyCountry'])->name('shipping.countries.destroy');
    Route::patch('shipping/countries/{country}/toggle', [App\Http\Controllers\Admin\ShippingController::class, 'toggleCountry'])->name('shipping.countries.toggle');
    
    // State routes
    Route::post('shipping/states', [App\Http\Controllers\Admin\ShippingController::class, 'storeState'])->name('shipping.states.store');
    Route::put('shipping/states/{state}', [App\Http\Controllers\Admin\ShippingController::class, 'updateState'])->name('shipping.states.update');
    Route::delete('shipping/states/{state}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyState'])->name('shipping.states.destroy');
    Route::patch('shipping/states/{state}/toggle', [App\Http\Controllers\Admin\ShippingController::class, 'toggleState'])->name('shipping.states.toggle');
    
    // API routes
    Route::get('shipping/countries/{country}/states/api', [App\Http\Controllers\Admin\ShippingController::class, 'getStatesByCountry'])->name('shipping.countries.states');
    Route::get('shipping/fee/{country}/{state?}', [App\Http\Controllers\Admin\ShippingController::class, 'getShippingFee'])->name('shipping.fee');
});

require __DIR__.'/auth.php';