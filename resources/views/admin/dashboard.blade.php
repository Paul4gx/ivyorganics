<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Products -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg" >
            <div class="p-6">
                <div class="text-gray-900 text-xl mb-2">Total Products</div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</div>
            </div>

        </div>

        <!-- Total Orders -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg relative">
            <div class="p-6">
                <div class="text-gray-900 text-xl mb-2">Total Orders</div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</div>
            </div>
            <div class="p-6 absolute bottom-0 right-0">
                <span class="text-gray-900 text-xs mb-2">Pending Orders</span>
                <span class="text-xl inline font-bold text-gray-900">{{ $pendingOrders }}</span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="text-gray-900 text-xl mb-2">Total Revenue(NGN)</div>
                <div class="text-3xl font-bold text-gray-900">₦{{ number_format($totalRevenueNG, 2) }}</div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="text-gray-900 text-xl mb-2">Total Revenue(USD)</div>
                <div class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenueUSD, 2) }}</div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$order->country === 'NG'? '₦' : '$';}}{{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($order->order_status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                           ($order->order_status === 'shipped' ? 'bg-purple-100 text-purple-800' : 
                                           ($order->order_status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                           'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Products</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topProducts as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->orders_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($product->price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 