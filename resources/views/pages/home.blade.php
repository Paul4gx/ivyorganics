@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="relative bg-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="md:flex items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-playfair font-bold text-green-800 mb-6">Pure. Natural. Organic.</h1>
                    <p class="text-lg text-gray-600 mb-8">Discover our handcrafted organic beauty and wellness products made with love and the finest natural ingredients.</p>
                    <a href="products.html" class="inline-block bg-green-700 hover:bg-green-800 text-white font-medium py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">Shop Now</a>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="relative w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden border-8 border-white shadow-xl">
                        <img src="{{asset('images/hero.jpg')}}" alt="Organic Products" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-white transform skew-y-1 -mb-8 z-10"></div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-playfair font-bold text-green-800 mb-4">Our Featured Products</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Each product is carefully crafted with organic ingredients to nourish your body and soul.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProducts as $product)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover transition duration-500 hover:scale-110">
                        <div class="absolute top-2 right-2 bg-green-700 text-white text-xs font-semibold px-2 py-1 rounded-full">Organic</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1 font-playfair"><a href="{{ route('products.show', $product['id']) }}" class="text-green-700 hover:text-green-800">{{ $product['name'] }}</a></h3>
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product['description'], 60) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-green-700">${{ number_format($product['price'], 2) }}</span>
                            <a href="{{ route('products.show', $product['id']) }}" class="text-green-700 hover:text-green-800 font-medium">View Product</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-block border-2 border-green-700 text-green-700 hover:bg-green-700 hover:text-white font-medium py-3 px-8 rounded-full transition duration-300">View All Products</a>
        </div>
    </section>
    <section class="bg-green-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-playfair font-bold mb-4">Why Choose Ivy Organics?</h2>
                <p class="text-green-100 max-w-2xl mx-auto">We're committed to your well-being and the health of our planet.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg bg-green-700 bg-opacity-30">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">100% Organic</h3>
                    <p class="text-green-100">All our ingredients are certified organic, free from harmful chemicals and pesticides.</p>
                </div>
                
                <div class="text-center p-6 rounded-lg bg-green-700 bg-opacity-30">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-recycle text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Eco-Friendly</h3>
                    <p class="text-green-100">Sustainable packaging and ethical sourcing to minimize our environmental impact.</p>
                </div>
                
                <div class="text-center p-6 rounded-lg bg-green-700 bg-opacity-30">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Cruelty-Free</h3>
                    <p class="text-green-100">Never tested on animals. We love all creatures great and small.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-playfair font-bold text-green-800 mb-4">What Our Customers Say</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Real experiences from people who love our products.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-semibold">Sarah Johnson</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">"The lavender body oil is divine! It's become part of my nightly routine and helps me sleep so much better."</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-semibold">Emily Chen</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">"I've struggled with sensitive skin for years. Ivy Organics is the only brand that doesn't cause irritation. Love their products!"</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                        <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Customer" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-semibold">Michael Brown</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">"The herbal tea blend is amazing. I drink it every evening and it helps me unwind after a long day at work."</p>
            </div>
        </div>
    </section>
@endsection