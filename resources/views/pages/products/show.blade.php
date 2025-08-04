@extends('layouts.main')

@section('title', $product['name'])

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="md:flex">
        <!-- Product Images -->
        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                @if(!empty($product['image']))
                <img id="main-product-image" src="{{ asset('storage/'.$product['image']) }}" alt="{{ $product['name'] ?? 'Product' }}" class="w-full h-96 object-contain">
                @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">No image available</span>
                </div>
                @endif
            </div>
            @if(!empty($product['image']))
            <div class="grid grid-cols-4 gap-2">
                @for($i = 1; $i <= 4; $i++)
                    <div class="cursor-pointer border-2 border-transparent hover:border-green-700 rounded">
                        <img src="{{ asset('storage/'.$product['image']) }}" alt="Thumbnail" class="w-full h-20 object-cover">
                    </div>
                @endfor
            </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div class="md:w-1/2">
            <h1 class="text-3xl font-playfair font-bold text-green-800 mb-2">{{ $product['name'] ?? 'Product Name Not Available' }}</h1>
            @if(!empty($product['tagline']))
            <p class="text-gray-600 mb-6">{{ $product['tagline'] }}</p>
            @endif
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400 mr-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= 4 || ($i == 5 && $product['id'] % 2 == 0))
                            <i class="fas fa-star"></i>
                        @else
                            <i class="fas fa-star-half-alt"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-gray-600">(24 reviews)</span>
            </div>
            
            <p class="text-2xl font-semibold text-green-700 mb-6">{{ format_currency($product['price']) }}</p>
            
            <p class="text-gray-600 mb-6">{{ $product['description'] ?? 'No description available.' }}</p>
            
            @if(!empty($product['Perfect_for']))
            <div class="mb-6">
                <h3 class="font-semibold text-sm">Perfect For:</h3>
                <p class="text-gray-600">{{ $product['Perfect_for'] }}</p>
            </div>
            @endif
            
            @if(!empty($product['size']))
            <div class="mb-6">
                <h3 class="font-semibold text-sm">Size</h3>
                <p class="text-gray-600">{{ $product['size'] }}</p>
            </div>
            @endif
          @if($product->quantity > 0)  
          <form action="{{ route('cart.update', $product['id']) }}" method="POST" class="flex items-center mb-8" id="update-cart-form">
    @csrf
    <div class="flex items-center border border-gray-300 rounded mr-4">
        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" id="decrement-qty">-</button>
        <span class="px-3 py-1 border-x border-gray-300" id="product-qty">{{$product->quantity}}</span>
        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" id="increment-qty">+</button>
    </div>
    <input type="hidden" name="quantity" id="quantity-input" value="1">
    <button type="submit" class="border border-green-700 hover:bg-green-50 text-green-800 font-medium py-2 px-8 rounded-full transition duration-300">
        Update Cart
    </button>
        <a href="#" class="ml-4 bg-green-700 hover:bg-green-800 text-white font-medium py-2 px-8 rounded-full transition duration-300">
        Proceed to Checkout
        </a>
</form>    

@else
<form action="{{ route('cart.add', $product['id']) }}" method="POST" class="flex items-center mb-8" id="add-to-cart-form">
    @csrf
    <div class="flex items-center border border-gray-300 rounded mr-4">
        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" id="decrement-qty">-</button>
        <span class="px-3 py-1 border-x border-gray-300" id="product-qty">1</span>
        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" id="increment-qty">+</button>
    </div>
    <input type="hidden" name="quantity" id="quantity-input" value="1">
    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-medium py-2 px-8 rounded-full transition duration-300">
        Add to Cart
    </button>
</form>
@endif
            @if(!empty($product['features']) && is_array($product['features']))
            <div class="border-t border-gray-200 pt-6">
                <h3 class="font-semibold text-lg mb-2">Features Include:</h3>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    @foreach ($product['features'] as $feature)
                       <li>{{ $feature }}</li> 
                    @endforeach
                </ul>
            </div>
            @endif
            @if(!empty($product['texture']) || !empty($product['color']) || !empty($product['scent']))
            <div class="border-t border-gray-200 mt-4 pt-4 grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @if(!empty($product['texture']))
                <div>
                    <h3 class="font-semibold text-sm">Texture:</h3>
                    <p class="text-gray-600 text-md">{{ $product['texture'] }}</p>
                </div>
                @endif
                @if(!empty($product['color']))
                <div>
                    <h3 class="font-semibold text-sm">Color</h3>
                    <p class="text-gray-600 text-md">{{ $product['color'] }}</p>
                </div>
                @endif
                @if(!empty($product['scent']))
                <div>
                    <h3 class="font-semibold text-sm">Scent</h3>
                    <p class="text-gray-600 text-md">{{ $product['scent'] }}</p>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</section>

<!-- Related Products -->
@if(!empty($relatedProducts) && count($relatedProducts) > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-200">
    <h2 class="text-2xl font-playfair font-bold text-green-800 mb-8 text-center">You May Also Like</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($relatedProducts as $related)
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative h-48 overflow-hidden">
                    @if(!empty($related['image']))
                    <img src="{{ asset('storage/'.$related['image']) }}" alt="{{ $related['name'] ?? 'Product' }}" class="w-full h-full object-cover transition duration-500 hover:scale-110">
                    @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                    @endif
                    <div class="absolute top-2 right-2 bg-green-700 text-white text-xs font-semibold px-2 py-1 rounded-full">Organic</div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1 font-playfair">
                        <a href="{{ route('products.show', $related['id']) }}" class="text-green-700 hover:text-green-800">
                            {{ $related['name'] ?? 'Product Name Not Available' }}
                        </a>
                    </h3>
                    @if(!empty($related['tagline']))
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($related['tagline'], 60) }}</p>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-green-700">{{ format_currency($related['price'] ?? 0) }}</span>
                        <a href="{{ route('products.show', $related['id']) }}" class="text-green-700 hover:text-green-800 font-medium">View Product</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
// Quantity controls
const incrementBtn = document.getElementById('increment-qty');
const decrementBtn = document.getElementById('decrement-qty');
const qtyElement = document.getElementById('product-qty');
const inputElement = document.getElementById('quantity-input');

if (incrementBtn && qtyElement && inputElement) {
    incrementBtn.addEventListener('click', function() {
        let qty = parseInt(qtyElement.textContent) || 1;
        qtyElement.textContent = qty + 1;
        inputElement.value = qty + 1;
    });
}

if (decrementBtn && qtyElement && inputElement) {
    decrementBtn.addEventListener('click', function() {
        let qty = parseInt(qtyElement.textContent) || 1;
        if (qty > 1) {
            qtyElement.textContent = qty - 1;
            inputElement.value = qty - 1;
        }
    });
}

// Add to cart with success message
const addToCartForm = document.getElementById('add-to-cart-form');
if (addToCartForm) {
    addToCartForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content
            },
            body: JSON.stringify({
                quantity: document.getElementById('quantity-input').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success('Product added to cart!');
                } else {
                    alert('Product added to cart!');
                }
                
                // Update cart counter in navbar
                if (typeof updateCartCounter === 'function') {
                    updateCartCounter();
                }
                
                // Optional: Redirect to cart page
                window.location.href = "{{ route('cart') }}";
            }
        })
        .catch(error => console.error('Error:', error));
    });
}
// function updateCartCounter() {
//     const cartCount = getCartCount();
//     const cartIcon = document.querySelector('.fa-shopping-cart').parentElement;
//     if (cartCount > 0) {
//         cartIcon.innerHTML = `<i class="fas fa-shopping-cart"></i> (${cartCount})`;
//     }
// }
// Update cart counter on page load
function updateCartCounter() {
    const count = getCartCount();
    console.log('Cart count:', count);
    const cartIcon = document.querySelector('.fa-shopping-cart');
    if (cartIcon && cartIcon.parentElement && count > 0) {
        cartIcon.parentElement.innerHTML = `
            <i class="fas fa-shopping-cart"></i>
            <span class="ml-1 bg-green-700 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                ${count}
            </span>
        `;
    }
}

// Add this to script.js
function getCartCount() {
    try {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        return cart.reduce((total, item) => total + (item.quantity || 0), 0);
    } catch (error) {
        console.error('Error parsing cart from localStorage:', error);
        return 0;
    }
}
// Image gallery
const thumbs = document.querySelectorAll('[id^="thumb-"]');
const mainImage = document.getElementById('main-product-image');

if (thumbs.length > 0 && mainImage) {
    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            if (this.src) {
                mainImage.src = this.src;
            }
        });
    });
}
</script>
@endpush