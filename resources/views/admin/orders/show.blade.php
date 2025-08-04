<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Order Status -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Order Status</h3>
                        <p class="mt-1 text-sm text-gray-500">Last updated {{ $order->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="order_status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Contact Details</h4>
                        <div class="mt-2">
                            <p class="text-sm text-gray-900">{{ $order->full_name }}</p>
                            <p class="text-sm text-gray-900">{{ $order->email }}</p>
                            <p class="text-sm text-gray-900">{{ $order->phone }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Shipping Address</h4>
                        <div class="mt-2">
                            <p class="text-sm text-gray-900">{{ $order->address }}</p>
                            <p class="text-sm text-gray-900">{{ $order->city }}</p>
                            <p class="text-sm text-gray-900">{{ $order->state }}</p>
                            <p class="text-sm text-gray-900">{{ $order->country }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if($item->product->image)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ format_currency($item->price) }}{{$order->country != 'NG'? ' ('.format_currency($item->price, 'USD').')':'';}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ format_currency($item->price * $item->quantity) }}{{$order->country != 'NG'? ' ('.format_currency($item->price * $item->quantity, 'USD').')':'';}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$order->country == 'NG'? '₦':'$';}}{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Shipping</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$order->country == 'NG'? '₦':'$';}}{{ number_format($order->shipping_fee, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{$order->country == 'NG'? '₦':'$';}}{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Payment Method</h4>
                        <p class="mt-2 text-sm text-gray-900">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Payment Status</h4>
                        <p class="mt-2 text-sm text-gray-900">{{ ucfirst($order->payment_status) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 