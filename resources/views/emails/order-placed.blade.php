@component('mail::message')
# New Order Placed

Hello {{ $order->customer->name }},

Your order has been placed successfully.

## Order Details:
- **Order ID:** {{ $order->id }}
- **Date:** {{ $order->created_at->format('d M Y h:i A') }}
- **Total Amount:** ${{ number_format($order->total_amount, 2) }}

@component('mail::table')
| Product       | Qty  | Price  |
| ------------- | ---- | ------ |
@foreach($order->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | ${{ number_format($item->price, 2) }} |
@endforeach
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
