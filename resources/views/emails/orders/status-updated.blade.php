@component('mail::message')
# Order Status Updated

Dear {{ $order->customer_name }},

Your order #{{ $order->id }} status has been updated to **{{ ucfirst($order->status) }}**.

@component('mail::table')
| Product | Quantity | Price |
|:--------|:---------|:------|
@foreach($order->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | ₦{{ number_format($item->price * $item->quantity, 2) }} |
@endforeach
@endcomponent

**Order Summary:**
- Subtotal: ₦{{ number_format($order->subtotal, 2) }}
- Shipping: ₦{{ number_format($order->shipping_fee, 2) }}
- Total: ₦{{ number_format($order->total_amount, 2) }}

@if($order->status === 'shipped')
Your order has been shipped and is on its way to you. You will receive tracking information soon.
@elseif($order->status === 'delivered')
Your order has been delivered. Thank you for shopping with us!
@elseif($order->status === 'cancelled')
Your order has been cancelled. If you have any questions, please contact our customer support.
@endif

@component('mail::button', ['url' => route('orders.show', $order)])
View Order Details
@endcomponent

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 