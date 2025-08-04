@extends('layouts.main')

@section('title', 'Your Cart')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-playfair font-bold text-green-800 mb-8">Your Shopping Cart</h1>
    
    @if(count($products) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="divide-y divide-gray-200">
                @foreach($products as $product)
                    <div class="p-6 flex flex-col md:flex-row items-center">
                        <div class="w-full md:w-1/4 mb-4 md:mb-0">
                            <img src="{{ asset('storage/'.$product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-48 object-cover rounded">
                        </div>
                        <div class="w-full md:w-2/4 px-4">
                            <h3 class="font-semibold text-lg text-green-800 font-playfair"><a href="{{ route('products.show', $product['id']) }}" class="text-green-700 hover:text-green-800">{{ $product['name'] }}</a></h3>
                            <p class="text-gray-600 mt-1">{{ $product['size'] }}</p>
                            <p class="text-gray-600 mt-1">{{ $product['tagline'] }}</p>
                        </div>
                        <div class="w-full md:w-1/4 mt-4 md:mt-0 flex flex-col items-end">
                            <p class="font-semibold text-green-700">{{ format_currency($product['price']) }}</p>
                            <div class="flex items-center mt-2">
                                                <form action="{{ route('cart.update', $product['id']) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="quantity" value="{{ $product['quantity'] - 1 }}">
                    <button type="submit" class="px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">
                        <i class="fas fa-minus"></i>
                    </button>
                </form>
                
                <span class="px-4 py-1">{{ $product['quantity'] }}</span>
                
                <form action="{{ route('cart.update', $product['id']) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="quantity" value="{{ $product['quantity'] + 1 }}">
                    <button type="submit" class="px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
                                <form action="{{ route('cart.remove', $product['id']) }}" method="POST" class="ml-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
    <h3 class="text-lg font-semibold">Total</h3>
    <p class="text-xl font-semibold text-green-700">
        {{ format_currency($products->sum(function($product) { return $product['price'] * $product['quantity']; })) }}
        {{-- <span class="text-sm font-normal text-gray-600">(includes $5.00 shipping)</span> --}}
    </p>
</div>
                
                <div class="mt-6 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="{{ route('products.index') }}" class="px-6 py-2 border border-green-700 text-green-700 hover:bg-green-50 rounded-lg text-center">
                        Continue Shopping
                    </a>
                    <a href="{{ route('checkout') }}" class="px-6 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-center">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h3>
            <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-green-700 hover:bg-green-800 text-white font-medium py-2 px-6 rounded-lg">
                Start Shopping
            </a>
        </div>
    @endif
</section>
@endsection