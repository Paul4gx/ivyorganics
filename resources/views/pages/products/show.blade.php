@extends('layouts.app')

@section('title', $product['name'])

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="md:flex">
        <!-- Product Images -->
        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <img id="main-product-image" src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-96 object-contain">
            </div>
            <div class="grid grid-cols-4 gap-2">
                @for($i = 1; $i <= 4; $i++)
                    <div class="cursor-pointer border-2 border-transparent hover:border-green-700 rounded">
                        <img src="{{ asset($product['image']) }}" alt="Thumbnail" class="w-full h-20 object-cover">
                    </div>
                @endfor
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="md:w-1/2">
            <h1 class="text-3xl font-playfair font-bold text-green-800 mb-2">{{ $product['name'] }}</h1>
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
            
            <p class="text-2xl font-semibold text-green-700 mb-6">${{ number_format($product['price'], 2) }}</p>
            
            <p class="text-gray-600 mb-6">{{ $product['description'] }}</p>
            
            <div class="mb-6">
                <h3 class="font-semibold text-sm">Perfect For:</h3>
                <p class="text-gray-600">{{ $product['Perfect_for'] }}</p>
            </div>
            
            <div class="mb-6">
                <h3 class="font-semibold text-sm">Size</h3>
                <p class="text-gray-600">{{ $product['size'] }}</p>
            </div>
            
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

            <div class="border-t border-gray-200 pt-6">
                <h3 class="font-semibold text-lg mb-2">Features Include:</h3>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    @foreach ($product['features'] as $features)
                       <li>{{$features}}</li> 
                    @endforeach
                </ul>
            </div>
            <div class="border-t border-gray-200 mt-4 pt-4 grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
  <div>
    <h3 class="font-semibold text-sm">Texture:</h3>
    <p class="text-gray-600 text-md">{{ $product['texture'] }}</p>
  </div>
  <div>
    <h3 class="font-semibold text-sm">Color</h3>
    <p class="text-gray-600 text-md">{{ $product['color'] }}</p>
  </div>
  <div>
    <h3 class="font-semibold text-sm">Scent</h3>
    <p class="text-gray-600 text-md">{{ $product['scent'] }}</p>
  </div>
</div>

        </div>
    </div>
</section>

<!-- Related Products -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-200">
    <h2 class="text-2xl font-playfair font-bold text-green-800 mb-8 text-center">You May Also Like</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($relatedProducts as $related)
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset($related['image']) }}" alt="{{ $related['name'] }}" class="w-full h-full object-cover transition duration-500 hover:scale-110">
                    <div class="absolute top-2 right-2 bg-green-700 text-white text-xs font-semibold px-2 py-1 rounded-full">Organic</div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1 font-playfair"><a href="{{ route('products.show', $related['id']) }}" class="text-green-700 hover:text-green-800">{{ $related['name'] }}</a></h3>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($related['tagline'], 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-green-700">${{ number_format($related['price'], 2) }}</span>
                        <a href="{{ route('products.show', $related['id']) }}" class="text-green-700 hover:text-green-800 font-medium">View Product</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection

@push('scripts')
<script>
// Quantity controls
document.getElementById('increment-qty').addEventListener('click', function() {
    const qtyElement = document.getElementById('product-qty');
    const inputElement = document.getElementById('quantity-input');
    let qty = parseInt(qtyElement.textContent);
    qtyElement.textContent = qty + 1;
    inputElement.value = qty + 1;
});

document.getElementById('decrement-qty').addEventListener('click', function() {
    const qtyElement = document.getElementById('product-qty');
    const inputElement = document.getElementById('quantity-input');
    let qty = parseInt(qtyElement.textContent);
    if (qty > 1) {
        qtyElement.textContent = qty - 1;
        inputElement.value = qty - 1;
    }
});

// Add to cart with success message
document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            quantity: document.getElementById('quantity-input').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Product added to cart!');
            
            // Update cart counter in navbar
            if (typeof updateCartCounter === 'function') {
                updateCartCounter();
            }
            
            // Optional: Redirect to cart page
            // window.location.href = "{{ route('cart') }}";
        }
    })
    .catch(error => console.error('Error:', error));
});

// Image gallery
const thumbs = document.querySelectorAll('[id^="thumb-"]');
thumbs.forEach(thumb => {
    thumb.addEventListener('click', function() {
        document.getElementById('main-product-image').src = this.src;
    });
});
</script>
@endpush