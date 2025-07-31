@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Parchases</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <!-- Add Button -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                                    + Add Parchase</button>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3 text-end pt-2">
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Search by name, type, category..." onkeyup="searchTable()">
                            </div>
                        </div>
                        <table class="table table-bordered table-striped" id="assetTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>invoice No</th>
                                    <th>Item Name</th>
                                    <th>Asset Type</th>
                                    <th>Variation</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Supplier by</th>
                                    <th>Date</th>
                                    <th>Bill No</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $purchase)
                                    <tr>
                                        <td>{{ $purchase->id }}</td>
                                        <td>{{ $purchase->invoice_no }}</td>
                                        <td>{{ $purchase->name }}</td>
                                        <td>{{ $purchase->asset_type }}</td>
                                        <td>{{ $purchase->variation_id ?? '-' }}</td>
                                        <td>{{ $purchase->total_quantity }}</td>
                                        <td>{{ $purchase->price }}</td>
                                        {{-- <td>
                                            <a href="#" class="price-link" data-id="{{ $asset->id }}"
                                                data-price="{{ $asset->price }}" data-supplier="{{ $asset->supplier_name }}"
                                                data-warehouse="{{ $asset->warehouse_name }}" data-bs-toggle="tooltip"
                                                title="Click to update">
                                                {{ $asset->price }}
                                            </a>
                                        </td> --}}

                                        <td>{{ $purchase->supplier_name }}</td>

                                        <td>{{ $purchase->purchase_date }}</td>
                                        <td>{{ $purchase->notes }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $purchase->id }}">Edit</button>

                                                <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                                                    data-bs-target="#priceModal">
                                                    + Edit Stock
                                                </button>
                                                <a href="{{ route('asset-price-history.index', $purchase->id) }}"
                                                    class="btn btn-dark">
                                                    View History
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $purchase->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <form method="POST" action="{{ route('purchases.update', $purchase->id) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title">Edit Asset</h5>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="name">Asset Name</label>
                                                                <input type="text" name="name"
                                                                    value="{{ $purchase->name }}" class="form-control"
                                                                    placeholder="Name" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="asset_type">Asset Type</label>
                                                                <input type="text" name="asset_type"
                                                                    value="{{ $purchase->asset_type }}" class="form-control"
                                                                    placeholder="Type" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="total_quantity">Total Quantity</label>
                                                                <input type="number" name="total_quantity"
                                                                    {{-- value="{{ $asset->total_quantity }}" --}} class="form-control"
                                                                    placeholder="Quantity" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="price">Price</label>
                                                                <input type="number" name="price"
                                                                    value="{{ $purchase->price }}" class="form-control"
                                                                    placeholder="Price">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="supplier_name">Supplier Name</label>
                                                                <input type="text" name="supplier_name"
                                                                    value="{{ $purchase->supplier_name }}"
                                                                    class="form-control" placeholder="Supplier" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="warehouse_name">Warehouse Name</label>
                                                                <input type="text" name="warehouse_name"
                                                                    value="{{ $purchase->warehouse_name }}"
                                                                    class="form-control" placeholder="Warehouse" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="purchase_date">Purchase Date</label>
                                                                <input type="date" name="purchase_date"
                                                                    value="{{ $purchase->purchase_date }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="notes">Notes</label>
                                                                <textarea name="notes" class="form-control" placeholder="Notes">{{ $purchase->notes }}</textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-success">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Price Update Modal -->
                                    <div class="modal fade" id="priceModal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form id="priceUpdateForm" method="POST" action="">
                                                @csrf
                                                <input type="hidden" name="asset_id" id="priceAssetId">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Update Price & Quantity</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>New Price</label>
                                                            <input type="number" name="new_price" id="new_price"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Additional Quantity</label>
                                                            <input type="number" name="additional_qty"
                                                                id="additional_qty" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="supplier_name">Supplier Name</label>
                                                            <input type="text" name="supplier_name" id="supplier_name"
                                                                class="form-control" placeholder="Supplier" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="warehouse_name">Warehouse Name</label>
                                                            <input type="text" name="warehouse_name"
                                                                id="warehouse_name" class="form-control"
                                                                placeholder="Warehouse" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Note (optional)</label>
                                                            <textarea name="note" class="form-control" placeholder="Optional note..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg"> {{-- wide modal for 2 columns --}}
            <form  id="addPurchaseForm" method="POST" action="{{ route('purchases.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Purchase</h5>
                    </div>
                    <div class="modal-body">

                        {{-- Item Type --}}
                        <div class="row">
                           @if (session('success'))
                                <div class="alert alert-danger">{{ session('success') }}</div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <label for="item_type">Item Type</label>
                                <select name="item_type" id="itemType" class="form-select" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="product">Product</option>
                                    <option value="asset">Asset</option>
                                </select>
                            </div>

                            {{-- Item Name --}}
                            <div class="col-md-6 mb-3">
                                <label for="item_id">Item Name</label>
                                <select name="item_id" id="itemSelect" class="form-select" required>
                                    <option value="">-- Select Item --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Variation Dropdown (for product only) --}}
                        <div class="col-md-6 mb-3" id="variationWrapper" style="display: none;">
                            <label for="variation_id">Product Variation</label>
                            <select name="variation_id" id="variationSelect" class="form-select">
                                <option value="">-- Select Variation --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_quantity">Total Quantity</label>
                                <input type="number" name="total_quantity" class="form-control" placeholder="Quantity"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price">Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Price">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_name">Supplier Name</label>
                                <input type="text" name="supplier_name" class="form-control" placeholder="Name"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="invoice_no">invoice_no</label>
                                <input type="number" name="invoice_no" class="form-control"
                                    placeholder="Main invoice_no">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date">Purchase Date</label>
                                <input type="date" name="purchase_date"
                                    value="{{ old('purchase_date', \Carbon\Carbon::now()->toDateString()) }}"
                                    class="form-control" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="notes">Notes</label>
                                <textarea name="notes" class="form-control" placeholder="Optional notes, bill number, etc."></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function searchTable() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toLowerCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('#itemType').change(function() {
            const type = $(this).val();
            $('#itemSelect').empty().append('<option value="">-- Select Item --</option>');
            $('#variationSelect').empty().append('<option value="">-- Select Variation --</option>');
            $('#variationWrapper').hide();

            if (type) {
                $.ajax({
                    url: '/get-items/' + type, // 👈 create this route
                    type: 'GET',
                    success: function(items) {
                        items.forEach(function(item) {
                            $('#itemSelect').append(
                                `<option value="${item.id}">${item.name}</option>`
                            );
                        });
                    },
                    error: function() {
                        alert('Failed to load items.');
                    }
                });
            }
        });

        $('#itemSelect').change(function() {
            const type = $('#itemType').val();
            const itemId = $(this).val();

            if (type === 'product' && itemId) {
                $.ajax({
                    url: '/get-product-variations/' + itemId,
                    type: 'GET',
                    success: function(variations) {
                        $('#variationSelect').empty().append(
                            '<option value="">-- Select Variation --</option>');
                        variations.forEach(function(v) {
                            $('#variationSelect').append(
                                `<option value="${v.id}">${v.unit} - ${v.size}</option>`
                            );
                        });
                        $('#variationWrapper').show(); // ✅ Make sure this runs
                    },
                    error: function() {
                        alert('Failed to load variations.');
                    }
                });
            } else {
                $('#variationWrapper').hide();
            }
        });


        //price modal
        $(document).ready(function() {
            $(document).on('click', '.price-link', function(e) {
                e.preventDefault();
                const assetId = $(this).data('id');
                const price = $(this).data('price');
                const supplier = $(this).data('supplier');
                const warehouse = $(this).data('warehouse');

                $('#priceAssetId').val(assetId);
                $('#new_price').val(price);
                $('#additional_qty').val('');
                $('#supplier_name').val(supplier);
                $('#warehouse_name').val(warehouse);

                $('#priceUpdateForm').attr('action', '/purchases/update-price/' +
                    assetId);
                $('#priceModal').modal('show');
            });
        });


    });
</script>



