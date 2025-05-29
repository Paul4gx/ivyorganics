@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <!-- Products Header -->
    <section class="bg-green-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-playfair font-bold text-green-800 mb-4">Our Organic Products</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Each product is crafted with care using the finest organic ingredients nature has to offer.</p>
        </div>
    </section>

    <!-- Products Filter -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div class="mb-4 md:mb-0">
                <label for="category-filter" class="sr-only">Filter by category</label>
                <select id="category-filter" class="border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                    <option value="all">All Categories</option>
                    <option value="body-care">Body Care</option>
                    <option value="hair-care">Hair Care</option>
                    <option value="full-package">Full Package</option>
                    <option value="face-care">Face Care</option>
                    <option value="wellness">Wellness</option>
                </select>
            </div>
            <div class="relative w-full md:w-64">
                <input type="text" id="search-products" placeholder="Search products..." class="w-full border border-gray-300 rounded-full px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="products-grid">
            @foreach($products as $product)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden" data-category="{{ $product['category'] }}">
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
    </section>
@endsection

@push('scripts')
<script>
// Filter products by category
document.getElementById('category-filter').addEventListener('change', function() {
    const category = this.value;
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Search products
document.getElementById('search-products').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        const description = card.querySelector('p').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>
@endpush