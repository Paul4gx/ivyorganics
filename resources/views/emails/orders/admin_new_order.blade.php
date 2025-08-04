<x-mail::message>
# New Order Placed

A new order <strong>#{{ $order->order_number }}</strong> has been placed and paid successfully.

**Customer:**
- Name: {{ $order->full_name }}
- Email: {{ $order->email }}
- Phone: {{ $order->phone }}
- Address: {{ $order->address }}, {{ $order->city }}, {{ $order->state }}, {{ $order->zip }}, {{ $order->country }}

<x-mail::table>
| Product | Quantity | Price |
|:--------|:---------|:------|
@foreach($order->order_items as $item)
| {{ $item['name'] }} | {{ $item['quantity'] }} | ₦{{ number_format($item['price'] * $item['quantity'], 2) }} |
@endforeach
</x-mail::table>

**Order Summary:**
- Subtotal: ₦{{ number_format($order->subtotal, 2) }}
- Shipping: ₦{{ number_format($order->shipping_fee, 2) }}
- Total: ₦{{ number_format($order->total, 2) }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
