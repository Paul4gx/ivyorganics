@php
    $currencies = [
        'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
        'USD' => ['name' => 'US Dollar', 'symbol' => '$']
    ];
    $currentCurrency = get_current_currency();
@endphp

<div x-data="{ open: false, currentCurrency: '{{ $currentCurrency }}' }" class="relative">
    <button @click="open = !open" 
            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none transition ease-in-out duration-150">
        <span class="mr-2">{{ $currencies[$currentCurrency]['symbol'] }}</span>
        <span class="mr-1">{{ $currentCurrency }}</span>
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
        <div class="py-1" role="menu" aria-orientation="vertical">
            @foreach($currencies as $code => $currency)
                <form method="POST" action="{{ route('currency.switch') }}" class="block">
                    @csrf
                    <input type="hidden" name="currency" value="{{ $code }}">
                    <button type="submit" 
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ $code === $currentCurrency ? 'bg-gray-100 text-gray-900' : '' }}"
                            role="menuitem">
                        <div class="flex items-center">
                            <span class="mr-2">{{ $currency['symbol'] }}</span>
                            <span>{{ $currency['name'] }}</span>
                            @if($code === $currentCurrency)
                                <svg class="ml-auto h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</div> 