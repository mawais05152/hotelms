<div class="container" style="padding:30px 15px; background:#f8f9fa; width:100%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.05); overflow:hidden;">
                <div class="card-header text-white text-center" style="background:#0d6efd; padding:20px;">
                    <h2 class="mb-0">
                        {{ $type === 'user' ? '🎉 Thank You for Your Order!' : '🧾 New Order Received' }}
                    </h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="mb-2">🆔 Order Details</h4>
                        <div class="mb-1"><strong>Order No:</strong> {{ $order->id }}</div>
                        <div><strong>Order Date:</strong> {{ $order->created_at->format('d M Y') }}</div>
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-2">🛒 Order Items</h4>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Variation</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order as $orders)
                                    <tr>
                                        <td>{{ $orders->product->category->name ?? 'N/A' }}</td>
                                        <td>{{ $orders->product->name ?? 'N/A' }}</td>
                                        {{ $orders->variation->unit }} {{ $orders->variation->size }}
                                        {{-- <td>{{ $orders->variation ? $orders->variation->unit . ' ' . $orders->variation->size : 'N/A' }}</td> --}}
                                        <td class="text-center">{{ $orders->quantity }}</td>
                                        <td class="text-end">${{ number_format($orders->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4 p-3 bg-light border rounded">
                        <h4 class="mb-2">💰 Order Summary</h4>
                        <p class="fw-bold fs-5 mb-0">Total: ${{ number_format($order->sub_total, 2) }}</p>
                    </div>

                    <div class="mb-4 text-center">
                        @if($type === 'user')
                            <p class="mb-1">Thanks for shopping with us ❤️</p>
                            <p class="mb-0">We hope to see you again soon!</p>
                        @else
                            <p class="text-danger fw-bold mb-0">
                                ⚠️ Action Required: Please process this order.
                            </p>
                        @endif
                    </div>

                    <div class="text-center">
                        <a href="{{ url('/orders/'.$order->id) }}" class="btn btn-primary btn-lg">
                            View Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
