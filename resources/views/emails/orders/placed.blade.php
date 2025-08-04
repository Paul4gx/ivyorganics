<x-mail::message>
# Order Confirmation

Dear {{ $order->full_name }},

Thank you for your order! Your order <strong>#{{ $order->order_number }}</strong> has been received and is now being processed.

<x-mail::table>
| Product | Quantity | Price |
|:--------|:---------|:------|
@foreach($order->order_items as $item)
| {{ $item['name'] }} | {{ $item['quantity'] }} | {{ format_currency($item['price'] * $item['quantity']) }} |
@endforeach
</x-mail::table>

**Order Summary:**
- Subtotal: {{ format_currency($order->subtotal) }}
- Shipping: {{ format_currency($order->shipping_fee) }}
- Total: {{ format_currency($order->total) }}

We will notify you when your order status changes.

If you have any questions, please contact us.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
