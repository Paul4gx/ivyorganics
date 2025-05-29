@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="md:flex">
        <!-- Order Summary -->
        <div class="md:w-2/5 md:pr-8 mb-8 md:mb-0">
            <h2 class="text-2xl font-playfair font-bold text-green-800 mb-6">Your Order</h2>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    @foreach($products as $product)
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded overflow-hidden mr-4">
                                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $product['name'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $product['size'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">${{ number_format($product['total'], 2) }}</p>
                                <p class="text-sm text-gray-600">{{ $product['quantity'] }} x ${{ number_format($product['price'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium">$5.00</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-3">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-lg font-semibold">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Payment Details</h3>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-green-800 font-semibold mb-2">Bank Transfer Instructions:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <li>Bank Name: Organic Bank</li>
                        <li>Account Name: Ivy Organics</li>
                        <li>Account Number: 1234567890</li>
                        <li>Sort Code: 04-00-75</li>
                    </ul>
                    <p class="mt-3 text-sm text-gray-600">Please include your order number as the payment reference.</p>
                </div>
            </div>
        </div>
        
        <!-- Checkout Form -->
        <div class="md:w-3/5">
            <h2 class="text-2xl font-playfair font-bold text-green-800 mb-6">Customer Information</h2>
            
            <form method="POST" action="{{ route('checkout.submit') }}" class="bg-white rounded-lg shadow-md p-6">
                @csrf
                
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first-name" class="block text-gray-700 mb-1">First Name *</label>
                            <input type="text" id="first-name" name="first_name" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                        <div>
                            <label for="last-name" class="block text-gray-700 mb-1">Last Name *</label>
                            <input type="text" id="last-name" name="last_name" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="email" class="block text-gray-700 mb-1">Email Address *</label>
                        <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                    </div>
                    
                    <div class="mt-4">
                        <label for="phone" class="block text-gray-700 mb-1">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4">Shipping Address</h3>
                    <div>
                        <label for="address" class="block text-gray-700 mb-1">Street Address *</label>
                        <input type="text" id="address" name="address" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label for="city" class="block text-gray-700 mb-1">City *</label>
                            <input type="text" id="city" name="city" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                        <div>
                            <label for="state" class="block text-gray-700 mb-1">State/Province *</label>
                            <input type="text" id="state" name="state" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                        <div>
                            <label for="zip" class="block text-gray-700 mb-1">ZIP/Postal Code *</label>
                            <input type="text" id="zip" name="zip" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="country" class="block text-gray-700 mb-1">Country *</label>
                        <select id="country" name="country" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                            <option value="">Select Country</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="UK">United Kingdom</option>
                            <option value="NG">Nigeria</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4">Transfer Information</h3>
                    <div>
                        <label for="transfer-name" class="block text-gray-700 mb-1">Name on Bank Account *</label>
                        <input type="text" id="transfer-name" name="transfer_name" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="transfer-amount" class="block text-gray-700 mb-1">Amount Transferred *</label>
                            <input type="text" id="transfer-amount" name="transfer_amount" value="${{ number_format($total, 2) }}" readonly required class="w-full border border-gray-300 rounded px-4 py-2 bg-gray-100">
                        </div>
                        <div>
                            <label for="transfer-date" class="block text-gray-700 mb-1">Transfer Date *</label>
                            <input type="date" id="transfer-date" name="transfer_date" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="transfer-reference" class="block text-gray-700 mb-1">Transfer Reference *</label>
                        <input type="text" id="transfer-reference" name="transfer_reference" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" class="mt-1 mr-2" required>
                        <span class="text-gray-700">I agree to the <a href="#" class="text-green-700 hover:underline">terms and conditions</a> and <a href="#" class="text-green-700 hover:underline">privacy policy</a></span>
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-medium py-3 px-6 rounded-lg transition duration-300">Complete Order</button>
            </form>
        </div>
    </div>
</section>
@endsection