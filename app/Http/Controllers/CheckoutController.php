<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingCountry;
use App\Models\ShippingState;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    public function index()
    {
        $cart = json_decode(request()->cookie('cart', '[]'), true);
        $products = Product::whereIn('id', array_column($cart, 'id'))
            ->get()
            ->map(function ($product) use ($cart) {
                $cartItem = collect($cart)->firstWhere('id', $product->id);
                $product->quantity = $cartItem['quantity'] ?? 1;
                $product->total = $product->price * $product->quantity;
                return $product;
            });
        
        $subtotal = $products->sum('total');
        $total = $subtotal;
        $countries = ShippingCountry::active()->with('states')->get();
        $currentCurrency = get_current_currency();
        
        return view('pages.checkout', compact('products', 'subtotal', 'total', 'countries', 'currentCurrency'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
        ]);

        $cart = json_decode($request->cookie('cart', '[]'), true);
        $products = Product::whereIn('id', array_column($cart, 'id'))->get();
        
        $orderItems = $products->map(function ($product) use ($cart) {
            $cartItem = collect($cart)->firstWhere('id', $product->id);
            $quantity = $cartItem['quantity'] ?? 1;
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'quantity' => $quantity,
                'total' => $product->price * $quantity
            ];
        });
        $subtotal = $orderItems->sum('total');
        
        // Calculate shipping fee based on country and state
        $shippingFee = 0;
        $country = ShippingCountry::where('code', $validated['country'])->first();
        
        if ($country) {
            if ($validated['state']) {
                $state = $country->states()->where('name', $validated['state'])->first();
                $shippingFee = $state ? $state->shipping_fee : $country->default_shipping_fee;
            } else {
                $shippingFee = $country->default_shipping_fee;
            }
        }
        
        $currency = ($validated['country'] === 'NG')? 'NGN' : 'USD';
        $subtotal = ($validated['country'] === 'NG')? $subtotal : format_currency_raw($subtotal, 'USD');
        $total = $subtotal + $shippingFee;
        log::info('total: '.$total);
        // Create order
        $order = Order::create([
            'order_number' => 'IVY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'country' => $validated['country'],
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'payment_method' => 'paystack',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'order_items' => $orderItems
        ]);

        // Initialize Paystack transaction
        $paystackData = [
            'amount' => (int) round($total * 100), // Convert to kobo as integer
            'email' => $validated['email'],
            'reference' => $order->order_number,
            'currency'=> $currency,
            'callback_url' => route('checkout.verify'),
            'metadata' => [
                'order_id' => $order->id,
                'custom_fields' => [
                    [
                        'display_name' => 'Order Number',
                        'variable_name' => 'order_number',
                        'value' => $order->order_number
                    ]
                ]
            ]
        ];
        $response = $this->paystack->initializeTransaction($paystackData);
        if ($response['status']) {
            return redirect($response['data']['authorization_url']);
        }
        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    public function verify(Request $request)
    {
        $reference = $request->reference;
        $response = $this->paystack->verifyTransaction($reference);

        if ($response['status'] && $response['data']['status'] === 'success') {
            $order = Order::where('order_number', $reference)->first();
            
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'paystack_reference' => $reference
                ]);

                // Send order confirmation email to customer
                Mail::to($order->email)->send(new \App\Mail\OrderPlaced($order));

                // Send new order notification to admin
                Mail::to(config('mail.from.admin_email'))->send(new \App\Mail\NewOrderAdmin($order));

                return redirect()->route('checkout.success')
                    ->with('order_id', $order->order_number)
                    ->withoutCookie('cart');
            }
        }

        return redirect()->route('checkout')
            ->with('error', 'Payment verification failed. Please try again.');
    }

    public function success()
    {
        if (!session()->has('order_id')) {
            return redirect()->route('home');
        }
        
        return view('checkout.success', [
            'orderId' => session('order_id')
        ]);
    }
}
