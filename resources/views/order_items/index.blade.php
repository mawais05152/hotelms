
@extends('layouts.master')
@section('content')

<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h4>Order Items for Order #{{ $order_id }}</h4>
        <a href="{{ url('/order-items/create') }}?order_id={{ $order_id }}" class="btn btn-success">Add Item</a>

    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->sub_total }}</td>
                        <td>
                            <a href="{{ route('order-items.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('order-items.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $total = $orderItems->sum('sub_total');
        @endphp

        <h5 class="mt-3">Total: PKR {{ $total }}</h5>
    </div>
</div>

@endsection




 {{-- @extends('layouts.master')
@section('content')
<!-- Items Modal -->
<div class="modal fade" id="itemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Order Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="itemsOrderId">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <select id="categorySelect" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="productSelect" class="form-select">
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="variationSelect" class="form-select">
                            <option value="">Select Variation</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <input type="number" id="quantityField" class="form-control" placeholder="Quantity" min="1" value="1">
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="priceField" class="form-control" placeholder="Price" readonly>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success w-100" id="addItemBtn">Add Item</button>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variation</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
     $('.manageItemsBtn').click(function(){
        let id = $(this).data('id');
        $('#itemsOrderId').val(id);
        loadItems(id);
        $('#itemsModal').modal('show');
    });

    $('#categorySelect').change(function(){
        let catId = $(this).val();
        $('#productSelect').html('<option value="">Select Product</option>');
        $('#variationSelect').html('<option value="">Select Variation</option>');
        $.get(`/products/by-category/${catId}`, res => {
            res.forEach(p => $('#productSelect').append(`<option value="${p.id}">${p.name}</option>`));
        });
    });

    $('#productSelect').change(function(){
        let prodId = $(this).val();
        $('#variationSelect').html('<option value="">Select Variation</option>');
        $.get(`/variations/by-product/${prodId}`, res => {
            res.forEach(v => $('#variationSelect').append(`<option value="${v.id}" data-price="${v.price}">${v.name}</option>`));
        });
    });

    $('#variationSelect').change(function(){
        $('#priceField').val($(this).find(':selected').data('price'));
    });

    $('#addItemBtn').click(function(){
        $.post(`/order-items`, {
            _token: '{{ csrf_token() }}',
            order_id: $('#itemsOrderId').val(),
            product_id: $('#productSelect').val(),
            variation_id: $('#variationSelect').val(),
            quantity: $('#quantityField').val()
        }, loadItems);
    });

    $('#searchInput').on('input', function(){
        let val = $(this).val().toLowerCase();
        $('#ordersTbody tr').each(function(){
            $(this).toggle($(this).text().toLowerCase().includes(val));
        });
    });

    function loadItems(orderId){
        $.get(`/order-items-list/${orderId}`, res => $('#itemsTableBody').html(res));
    }
</script> --}}
