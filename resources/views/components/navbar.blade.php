<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-playfair text-green-700 font-bold">Ivy Organics</span>
                </a>
            </div>
            <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">Home</x-nav-link>
                <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">Products</x-nav-link>
                {{-- <x-nav-link href="#">About</x-nav-link>
                <x-nav-link href="#">Contact</x-nav-link> --}}
                <x-nav-link href="{{ route('cart') }}">
                    <i class="fas fa-shopping-cart"></i>
                    @if($cartCount > 0)
                        <span class="ml-1 bg-green-700 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </x-nav-link>
            </div>
            <div class="-mr-2 flex items-center md:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-green-700 focus:outline-none" id="mobile-menu-button">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="hidden md:hidden bg-white shadow-lg" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <x-mobile-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">Home</x-mobile-nav-link>
            <x-mobile-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">Products</x-mobile-nav-link>
            {{-- <x-mobile-nav-link href="#">About</x-mobile-nav-link>
            <x-mobile-nav-link href="#">Contact</x-mobile-nav-link> --}}
            <x-mobile-nav-link href="{{ route('cart') }}">
                <i class="fas fa-shopping-cart mr-2"></i> Cart
                @if($cartCount > 0)
                    <span class="ml-1 bg-green-700 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </x-mobile-nav-link>
        </div>
    </div>
</nav>