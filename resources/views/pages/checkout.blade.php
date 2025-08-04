@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="md:flex">
                <!-- Checkout Form -->
        <div class="md:w-3/5 md:pr-8">
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
                            <select id="state" name="state" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                                <option value="">Select a country first</option>
                            </select>
                        </div>
                        <div>
                            <label for="zip" class="block text-gray-700 mb-1">ZIP/Postal Code *</label>
                            <input type="text" id="zip" name="zip" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="country" class="block text-gray-700 mb-1">Country *</label>
                        <select id="country" name="country" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-transparent">
                            <option value="">Select a country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->code }}" 
                                        data-default-fee="{{ $country->default_shipping_fee }}"
                                        {{ (get_current_currency() === 'NGN' && $country->code === 'NG') || (get_current_currency() === 'USD' && $country->code === 'US') ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
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
        <!-- Order Summary -->
        <div class="md:w-2/5 mb-8 md:mb-0">
            <h2 class="text-2xl font-playfair font-bold text-green-800 mb-6">Your Order</h2>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    @foreach($products as $product)
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                <div class="w-16 h-16 rounded overflow-hidden mr-4">
                                    <img src="{{ asset('storage/'.$product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $product['name'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $product['size'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">{{ format_currency($product['total']) }}</p>
                                <p class="text-sm text-gray-600">{{ $product['quantity'] }} x {{ format_currency($product['price']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">{{ format_currency($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium" id="shipping-amount">{{ format_currency(0.00) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-3">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-lg font-semibold" id="total-amount">{{ format_currency($subtotal) }}</span>
                    </div>

                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Payment Method</h3>
                <div class="bg-green-50 p-4 rounded-lg">
                    <img src="{{asset('images/paystack-ii.webp')}}">
                </div>
            </div>
        </div>
        

    </div>
</section>
@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const countrySelect = document.getElementById('country');
        const stateSelect = document.getElementById('state');
        const shippingAmount = document.getElementById('shipping-amount');
        const totalAmount = document.getElementById('total-amount');

        // Get subtotal from backend (passed into JS safely)
        const subtotal = parseFloat("{{ format_currency_raw($subtotal) }}");
        const currentCurrency = "{{ get_current_currency() }}";
        const currencySymbol = "{{ get_currency_symbol() }}";

        // Countries data from backend
        const countries = @json($countries);

        // Function to format currency
        function formatCurrency(amount) {
            return currencySymbol + parseFloat(amount).toFixed(2);
        }

        // Function to update shipping and total
        function updateShippingAndTotal(shippingFee) {
            const shippingFeeNum = parseFloat(shippingFee) || 0;
            shippingAmount.innerText = formatCurrency(shippingFeeNum);
            const newTotal = subtotal + shippingFeeNum;
            totalAmount.innerText = formatCurrency(newTotal);
        }

        // Function to switch currency via AJAX
        function switchCurrency(newCurrency) {
            // Show loading indicator
            const loadingMsg = document.createElement('div');
            loadingMsg.id = 'currency-loading';
            loadingMsg.innerHTML = `
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-700 mr-3"></div>
                            <span>Switching to ${newCurrency === 'NGN' ? 'Nigerian Naira' : 'US Dollar'}...</span>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(loadingMsg);

            fetch('{{ route("currency.switch.ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    currency: newCurrency
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to reflect the new currency
                    window.location.reload();
                } else {
                    // Remove loading indicator and show error
                    document.getElementById('currency-loading')?.remove();
                    alert('Failed to switch currency. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error switching currency:', error);
                // Remove loading indicator and show error
                document.getElementById('currency-loading')?.remove();
                alert('Failed to switch currency. Please try again.');
            });
        }

        // Initialize with default shipping fee if country is pre-selected
        if (countrySelect.value) {
            const selectedCountry = countries.find(c => c.code === countrySelect.value);
            if (selectedCountry) {
                updateShippingAndTotal(selectedCountry.default_shipping_fee);
                
                // Populate states for pre-selected country
                if (selectedCountry.states && selectedCountry.states.length > 0) {
                    stateSelect.innerHTML = '<option value="">Select a state/province</option>';
                    selectedCountry.states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.name;
                        option.textContent = state.name;
                        option.setAttribute('data-price', state.shipping_fee);
                        stateSelect.appendChild(option);
                    });
                }
            }
        }

        countrySelect.addEventListener('change', function () {
            const selectedCountryCode = countrySelect.value;
            const selectedCountry = countries.find(c => c.code === selectedCountryCode);
            
            // Clear and populate states
            stateSelect.innerHTML = '<option value="">Select a state/province</option>';
            
            if (selectedCountry && selectedCountry.states) {
                selectedCountry.states.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.name;
                    option.textContent = state.name;
                    option.setAttribute('data-price', state.shipping_fee);
                    stateSelect.appendChild(option);
                });
            }
            
            // Set default shipping fee for the country
            if (selectedCountry) {
                updateShippingAndTotal(selectedCountry.default_shipping_fee);
            } else {
                updateShippingAndTotal(0);
            }

            // Switch currency based on country selection
            if (selectedCountryCode) {
                let newCurrency = 'USD'; // Default to USD for international
                if (selectedCountryCode === 'NG') {
                    newCurrency = 'NGN'; // Naira for Nigeria
                }
                
                // Only switch if currency is different
                if (newCurrency !== currentCurrency) {
                    switchCurrency(newCurrency);
                }
            }
        });

        stateSelect.addEventListener('change', function () {
            const selectedOption = stateSelect.options[stateSelect.selectedIndex];
            let shippingPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            
            // If no specific state price, use country default
            if (shippingPrice === 0) {
                const selectedCountryCode = countrySelect.value;
                const selectedCountry = countries.find(c => c.code === selectedCountryCode);
                if (selectedCountry) {
                    shippingPrice = parseFloat(selectedCountry.default_shipping_fee);
                }
            }
            
            updateShippingAndTotal(shippingPrice);
        });
    });
</script>

@endpush
@endsection