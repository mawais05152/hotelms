@extends('layouts.master')
@section('content')
    @php use Carbon\Carbon; @endphp
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Orders & Items Management</h4>
            <button class="btn btn-success" id="addOrderBtn">+ Add Order</button>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <input type="text" id="searchInput" class="form-control w-25" style="height: 38px;"
                    placeholder="Search by  Product, Status...">
            </div>
            <table class="table table-bordered table-striped">
                <div id="printContainer" style="display: none;"></div>
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Table</th>
                        <th>Persons</th>
                        <th>Order By</th>
                        <th>Delivered By</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>category/product</th>
                        <th>Variation</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ordersTbody">
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->bookingTable->table_number ?? 'N/A' }}</td>
                            <td>{{ $order->person }}</td>
                            <td>{{ $order->orderedBy->name }}</td>
                            <td>{{ $order->deliveredBy->name }}</td>
                            <td>{{ $order->order_type }}</td>
                            <td>{{ $order->date }}</td>
                            <td>{{ $order->time }}</td>
                            <td>
                                <ul class="mb-1">
                                    @foreach ($order->orderItems as $item)
                                        <li>
                                            {{ $item->product->category->name ?? 'N/A' }} -
                                            {{ $item->product->name }}
                                            (Qty: {{ $item->quantity }}, Price: {{ $item->price }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="mb-1">
                                    @foreach ($order->orderItems as $item)
                                        <li>
                                            {{-- {{ $item->variation->size ?? 'N/A' }} {{ $item->variation->unit ?? '' }} --}}
                                            {{-- Or use a custom format --}}
                                            {{ $item->variation ? $item->variation->size . ' ' . $item->variation->unit : 'N/A' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $order->orderItems->sum(function ($item) {return $item->price;}) }}</td>
                            <td>
                                <a href="#" class="update-status-btn" data-order-id="{{ $order->id }}"
                                    data-status-url="{{ route('orders.status.store', $order->id) }}"
                                    data-status="{{ $order->status }}">
                                    {{ $order->status }}
                                </a>
                            </td>
                            <td class="d-flex flex-wrap gap-1">
                                <button class="btn btn-warning btn-sm editOrderBtn"
                                    data-order='@json($order)'>Edit</button>
                                <form action="{{ url('/orders/' . $order->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <button type="button" class="btn btn-secondary btn-sm printOrderBtn">Print</button>
                                <a href="{{ url('order-status/index/' . $order->id) }}"
                                    class="btn btn-info btn-sm">History</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="orderForm" method="POST" action="{{ url('/orders') }}">
                    @csrf
                    <input type="hidden" name="id" id="order_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Add / Edit Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="order_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Select Table (<span id="tableName"></span>)</label>
                                <select name="booking_id" class="form-select" required>
                                    <option value="">Select Table</option>
                                    @foreach ($tables as $table)
                                        <option value="{{ $table->id }}"
                                            {{ in_array($table->id, $pendingTableIds) ? 'disabled' : '' }}>
                                            {{ $table->table_number }}
                                            {{ in_array($table->id, $pendingTableIds) ? '(Not Available)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Pers</label>
                                <input type="number" name="person" class="form-control" placeholder="Persons"
                                    min="1" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ Carbon::now()->format('Y-m-d') }}" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Time</label>
                                <input type="time" name="time" class="form-control"
                                    value="{{ \Carbon\Carbon::now()->format('H:i') }}" required readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Order Taken By (<span id="orderByName"></span>)</label>
                                <select name="order_by" class="form-select" required>
                                    <option value="">Order Taken By</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Delivered By (<span id="deliveredByName"></span>)</label>
                                <select name="delivered_by" class="form-select" required>
                                    <option value="">Delivered By</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Order Type</label>
                                <select name="order_type" class="form-select" required>
                                    <option value="">Order Type</option>
                                    <option value="Dine In">Dine In</option>
                                    <option value="Parcel">Parcel</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Select Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="taken">Taken by</option>
                                    <option value="pending">Pending</option>
                                    <option value="delivered">Delivered by</option>
                                    <option value="completed">Completed</option>
                                    <option value="replaced">Replaced</option>
                                    <option value="cancelled">Cancel</option>
                                </select>
                            </div>
                        </div>

                        <div id="order-items">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Category</label>
                                    <select id="categorySelect" class="form-select category-select" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>Select Item</label>
                                    <input type="hidden" name="product_id[]" value="">
                                    <select name="product_id[]" class="form-select product-select" required>
                                        <option value="">Select Item</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Variation</label>
                                    <select name="variation_id[]" class="form-select variation-select" required>
                                        <option value="">Select Variation</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity[]" class="form-control quantity" min="1"
                                        value="1" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label>Price</label>
                                    <input type="number" name="price[]" class="form-control price" readonly>
                                </div>
                                <div class="col-md-1 mb-3 d-flex align-items-center">
                                    <button type="button" class="btn btn-success add-order-item mt-4">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Total Price</label>
                                <input type="number" id="total-price" name="sub_total" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
                <div id="order-item-template" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Category</label>
                            <select id="categorySelect" class="form-select category-select" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Select Item</label>
                            <select name="product_id[]" class="form-select product-select" required>
                                <option value="">Select Item</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Variation</label>
                            <select name="variation_id[]" class="form-select variation-select" required>
                                <option value="">Select Variation</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity[]" class="form-control quantity" min="1"
                                value="1" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Price</label>
                            <input type="number" name="price[]" class="form-control price" readonly>
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-center">
                            <button type="button" class="btn btn-danger remove-order-item mt-4">-</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="statusForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="order_id" id="statusOrderId">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="taken">Taken</option>
                                <option value="delivered">Delivered</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="replaced">Replaced</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Delivered By</label>
                            <select name="delivered_by" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.update-status-btn').on('click', function(e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                var statusUrl = $(this).data('status-url');
                var status = $(this).data('status');

                $('#statusOrderId').val(orderId);
                $('#statusForm').attr('action', statusUrl);
                $('select[name="status"]').val(status);

                $('#statusModal').modal('show');
            });
        });

        //

        $(document).on('change', '.category-select', function() {
            var categoryId = $(this).val();
            var productSelect = $(this).closest('.row').find('.product-select');
            var variationSelect = $(this).closest('.row').find('.variation-select');
            productSelect.empty();
            variationSelect.empty();
            variationSelect.append('<option value="">Select Variation</option>');
            if (categoryId) {
                $.ajax({
                    type: 'GET',
                    url: '/get-products-by-category/' + categoryId,
                    success: function(data) {
                        productSelect.append('<option value="">Select Product</option>');
                        $.each(data, function(index, product) {
                            if (product.type == 'Dishes') {
                                productSelect.append('<option value="' + product.id +
                                    '" data-type="Dishes">' + product.meal_name +
                                    '</option>');
                            } else {
                                productSelect.append('<option value="' + product.id +
                                    '" data-type="product">' + product.name + '</option>');
                            }
                        });
                    }
                });
            } else {
                productSelect.append('<option value="">Select Product</option>');
            }
        });
        $(document).on('input', '.quantity', function() {
            var quantity = $(this).val();
            if ($.isNumeric(quantity) && quantity > 0) {
                var variationSelect = $(this).closest('.row').find('.variation-select');
                var variation = variationSelect.find('option:selected');
                var price = variation.data('price');
                var priceInput = $(this).closest('.row').find('.price');
                priceInput.val(price * quantity);
                updateTotalPrice();
            }
        });
        $(document).on('change', '.variation-select', function() {
            var variationId = $(this).val();
            var productType = $(this).closest('.row').find('.product-select').find('option:selected').data('type');
            var priceInput = $(this).closest('.row').find('.price');
            var quantityInput = $(this).closest('.row').find('.quantity');
            var variation = $(this).find('option:selected');
            var price = variation.data('price');
            priceInput.val(price * quantityInput.val());
            updateTotalPrice();
        });

        $(document).on('change', '.product-select', function() {
            var productId = $(this).val();
            var productType = $(this).find('option:selected').data('type');
            var variationSelect = $(this).closest('.row').find('.variation-select');
            variationSelect.empty();
            variationSelect.append('<option value="">Select Variation</option>');
            if (productId && productType == 'Dishes') {
                $.ajax({
                    type: 'GET',
                    url: '/get-dish-variations/' + productId,
                    success: function(data) {
                        $.each(data, function(index, variation) {
                            variationSelect.append('<option value="' + variation.id +
                                '" data-price="' + variation.price + '">' +
                                variation.variation_name + '</option>');
                        });
                    }
                });
            } else if (productId && productType == 'product') {
                $.ajax({
                    type: 'GET',
                    url: '/get-product-variations/' + productId,
                    success: function(data) {
                        $.each(data, function(index, variation) {
                            variationSelect.append('<option value="' + variation.id +
                                '" data-price="' + variation.price + '">' +
                                variation.unit + ' - ' + variation.size + '</option>');
                        });
                    }
                });
            }
        });

        $(document).on('click', '.add-order-item', function() {
            var template = $('#order-item-template').html();
            $('#order-items').append(template);
        });

        $(document).on('click', '.remove-order-item', function() {
            $(this).closest('.row').remove();
            updateTotalPrice();
        });

        function updateTotalPrice() {
            var totalPrice = 0;
            $('.price').each(function() {
                if (!isNaN($(this).val()) && $(this).val() != '') {
                    totalPrice += parseFloat($(this).val());
                }
            });
            $('#total-price').val(totalPrice.toFixed(2));
        }

        $(document).ready(function() {
            $('#addOrderBtn').click(function() {
                $('#orderForm')[0].reset();
                $('#order_id').val('');
                $('#order-items').children('.row').not(':first').remove();
                $('#order-items').find('select, input').val('');
                $('#total-price').val(0);

                var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
                myModal.show();
            });


            $(document).on('click', '.editOrderBtn', function() {
                let orderData = $(this).data('order');

                $('#orderModal').modal('show');

                // Set form action
                $('#orderForm').attr('action', '/orders/' + orderData.id);
                $('#orderForm').append('<input type="hidden" name="_method" value="PUT">');

                $('#order_id').val(orderData.id);
                $('select[name="booking_id"]').val(orderData.booking_id);
                $('input[name="person"]').val(orderData.person);
                $('input[name="date"]').val(orderData.date);
                $('input[name="time"]').val(orderData.time);
                $('select[name="order_by"]').val(orderData.order_by);
                $('select[name="delivered_by"]').val(orderData.delivered_by);
                $('select[name="order_type"]').val(orderData.order_type);
                $('select[name="status"]').val(orderData.status);
                $('#total-price').val(orderData.sub_total);

                // Clear previous items except first row
                $('#order-items .row').not(':first').remove();

                if (orderData.order_items.length > 0) {
                    let firstItem = orderData.order_items[0];
                    $('#order-items .row:first .category-select').val(firstItem.product.category_id);
                    populateProducts(firstItem.product.category_id, $(
                        '#order-items .row:first .product-select'), firstItem.product_id);
                    $('#order-items .row:first .quantity').val(firstItem.quantity);
                    $('#order-items .row:first .price').val(firstItem.price);
                }

                for (let i = 1; i < orderData.order_items.length; i++) {
                    let item = orderData.order_items[i];
                    let template = $('#order-item-template').html();
                    $('#order-items').append(template);

                    let lastRow = $('#order-items .row').last();
                    lastRow.find('.category-select').val(item.product.category_id);
                    populateProducts(item.product.category_id, lastRow.find('.product-select'), item
                        .product_id);
                    lastRow.find('.quantity').val(item.quantity);
                    lastRow.find('.price').val(item.price);
                }

                calculateTotal();

            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                let searchValue = $(this).val().toLowerCase().trim();

                $('#ordersTbody tr').each(function() {
                    let tds = $(this).find('td');

                    let orderId = tds.eq(0).text().trim().toLowerCase(); // ID column
                    let tableNumber = tds.eq(1).text().trim().toLowerCase(); // Table column

                    // ✅ Show all rows if input is empty
                    if (searchValue === '') {
                        $(this).show();
                    }
                    // ✅ Show if exact match
                    else if (searchValue === orderId || searchValue === tableNumber) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $(document).on('click', '.printOrderBtn', function() {
                // Clone the full table row
                let row = $(this).closest('tr').clone();

                // Clean action buttons from the cloned row (Edit/Delete/Print)
                row.find('td:last').html(''); // remove buttons from last column

                // Create full table with headers
                let tableHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Table</th>
                        <th>Persons</th>
                        <th>Order By</th>
                        <th>Delivered By</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>category/product</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>${row.prop('outerHTML')}</tbody>
            </table>
        `;

                // Add to print container
                $('#printContainer').html(tableHTML);

                // Open new window and print
                let printWindow = window.open('', '_blank');
                printWindow.document.write(`
            <html>
                <head>
                    <title>Order Print</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                </head>
                <body>
                    <div class="container mt-4">
                        ${tableHTML}
                    </div>
                </body>
            </html>
        `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.printOrderBtn', function() {
                let row = $(this).closest('tr').clone();
                row.find('td:last').html(''); // Remove action buttons

                // Only extract <td> elements, not full <tr>
                let rowContent = row.html().trim();

                let tableHTML = `
                <table class="table table-bordered border-dark table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Table</th>
                            <th>Persons</th>
                            <th>Order By</th>
                            <th>Delivered By</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>category/product</th>
                            <th>Total</th>
                            <th>Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>${rowContent}</tr>
                    </tbody>
                </table>
            `;

                $('#printContainer').html(tableHTML);

                let printWindow = window.open('', '_blank');
                printWindow.document.write(`
                <html>
                    <head>
                        <title>Order Print</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                        <style>
                            body {
                                margin: 20px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse !important;
                            }
                            th, td {
                                border: 1px solid #000 !important;
                                padding: 8px;
                                text-align: center;
                            }
                            thead {
                                background-color: #343a40;
                                color: white;
                            }
                            tbody tr:nth-child(even) {
                                background-color: #f2f2f2;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container mt-4">
                            ${tableHTML}
                        </div>
                    </body>
                </html>
            `);

                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            });
        });
    </script>
    {{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        function fetchItems(categoryId, productSelect) {
            fetch(`/api/category/${categoryId}/items`)
                .then(res => res.json())
                .then(data => {
                    productSelect.innerHTML = `<option value="">Select Item</option>`;
                    data.items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.text = item.name;
                        productSelect.appendChild(option);
                    });
                });
        }

        function fetchVariations(itemId, variationSelect) {
            fetch(`/api/item/${itemId}/variations`)
                .then(res => res.json())
                .then(data => {
                    variationSelect.innerHTML = `<option value="">Select Variation</option>`;
                    data.variations.forEach(variation => {
                        const option = document.createElement('option');
                        option.value = variation.id;
                        option.text = `${variation.name} - Rs. ${variation.price}`;
                        variationSelect.appendChild(option);
                    });
                });
        }

        // On category change
        document.body.addEventListener('change', function (e) {
            if (e.target.classList.contains('category-select')) {
                const categoryId = e.target.value;
                const wrapper = e.target.closest('.row');
                const productSelect = wrapper.querySelector('.product-select');
                const variationSelect = wrapper.querySelector('.variation-select');
                productSelect.innerHTML = '<option value="">Loading...</option>';
                fetchItems(categoryId, productSelect);
                variationSelect.innerHTML = '<option value="">Select Variation</option>';
            }

            if (e.target.classList.contains('product-select')) {
                const itemId = e.target.value;
                const wrapper = e.target.closest('.row');
                const variationSelect = wrapper.querySelector('.variation-select');
                variationSelect.innerHTML = '<option value="">Loading...</option>';
                fetchVariations(itemId, variationSelect);
            }
        });
    });
</script> --}}
@endsection
