@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-check text-green-600 text-2xl"></i>
    </div>
    <h3 class="text-2xl font-playfair font-bold text-green-800 mb-2">Order Placed Successfully!</h3>
    <p class="text-gray-600 mb-6">Thank you for your purchase. We've sent a confirmation to your email.</p>
    <a href="https://wa.me/1234567890" class="inline-block bg-green-700 hover:bg-green-800 text-white font-medium py-2 px-6 rounded-full transition duration-300">
        <i class="fab fa-whatsapp mr-2"></i> Chat with Customer Care
    </a>
    <p class="mt-4 text-sm text-gray-500">Order ID: <span class="font-medium">{{ $orderId }}</span></p>
    
    <div class="mt-8">
        <a href="{{ route('products.index') }}" class="text-green-700 hover:text-green-800 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
        </a>
    </div>
</div>
@endsection