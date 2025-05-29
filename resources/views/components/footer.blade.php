<footer class="bg-green-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-playfair font-bold mb-4">Ivy Organics</h3>
                <p class="text-green-100 mb-4">Pure, natural products for your body and home.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-green-100 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-green-100 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-green-100 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-green-100 hover:text-white"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-lg mb-4">Shop</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('products.index') }}" class="text-green-100 hover:text-white">All Products</a></li>
                    <li><a href="{{ route('products.index') }}?category=body-care" class="text-green-100 hover:text-white">Body Care</a></li>
                    <li><a href="{{ route('products.index') }}?category=hair-care" class="text-green-100 hover:text-white">Hair Care</a></li>
                    <li><a href="{{ route('products.index') }}?category=face-care" class="text-green-100 hover:text-white">Face Care</a></li>
                    <li><a href="{{ route('products.index') }}?category=wellness" class="text-green-100 hover:text-white">Wellness</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold text-lg mb-4">About</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-green-100 hover:text-white">Our Story</a></li>
                    <li><a href="#" class="text-green-100 hover:text-white">Ingredients</a></li>
                    <li><a href="#" class="text-green-100 hover:text-white">Sustainability</a></li>
                    <li><a href="#" class="text-green-100 hover:text-white">Blog</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold text-lg mb-4">Contact</h4>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-2 text-green-300"></i>
                        <span class="text-green-100">123 Green Street, Organic City</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone-alt mt-1 mr-2 text-green-300"></i>
                        <span class="text-green-100">+1 (555) 123-4567</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope mt-1 mr-2 text-green-300"></i>
                        <span class="text-green-100">hello@ivyorganics.com</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-green-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-green-100 text-sm mb-4 md:mb-0">© {{ date('Y') }} Ivy Organics. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="text-green-100 hover:text-white text-sm">Privacy Policy</a>
                <a href="#" class="text-green-100 hover:text-white text-sm">Terms of Service</a>
                <a href="#" class="text-green-100 hover:text-white text-sm">Shipping Policy</a>
            </div>
        </div>
    </div>
</footer>