@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4>Mess Items List</h4>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">+ Add </button>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped table-hover mt-3" id="mess-itemsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Mess Purchase</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Price/Unit</th>
                                <th>Total Cost</th>
                                <th>Purchased By</th>
                                <th>Purchased At</th>
                                <th>Invoice</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($purchases as $purchase)
                            <tr data-id="{{ $purchase->id }}">
                                <td>{{ $purchase->id }}</td>
                                <td>{{ $purchase->ingredient_name }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>{{ $purchase->unit }}</td>
                                <td>{{ $purchase->price_per_unit }}</td>
                                <td>{{ $purchase->total_cost }}</td>
                                <td>{{ $purchase->purchased_by }}</td>
                                <td>{{ $purchase->purchased_at }}</td>
                                 <td>
                                    @if($purchase->invoice_no)
                                        <a href="{{ route('purchases.showInvoice', $purchase->invoice_no) }}" class="btn btn-info" target="_blank">View Invoice</a>
                                    @else
                                        <span class="text-muted">No Invoice</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- <a href="{{ route('mess_purchases.invoices', $purchase->invoice_no) }}" class="btn btn-sm btn-info" target="_blank">View Invoice</a> --}}

                                        <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                                        <form action="{{ route('mess_items_purchases.destroy', $purchase->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Single Edit Modal (will be populated dynamically) -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" action="" class="modal-content" id="editForm">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Purchase</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" id="editModalBody">
                                    <!-- Dynamic content injected by JS -->
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Create Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" action="{{ route('mess_items_purchases.store') }}" class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Purchase</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="purchase-items">
                                        <div class="purchase-item row mb-3 border p-2">
                                            <div class="col-md-4">
                                                <label>Mess Item Name</label>
                                                <input type="text" name="purchases[0][ingredient_name]" class="form-control" autofocus required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Quantity</label>
                                                <input type="number" name="purchases[0][quantity]" class="form-control quantity" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Unit</label>
                                                <input type="text" name="purchases[0][unit]" class="form-control" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Price Per Unit</label>
                                                <input type="number" name="purchases[0][price_per_unit]" class="form-control price-per-unit" step="0.01" required>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end pb-1">
                                                <button type="button" class="btn btn-success btn-sm add-item me-1">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Total Cost</label>
                                            <input type="number" name="total_cost" class="form-control total-cost" step="0.01" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Purchased By</label>
                                            <input type="text" name="purchased_by" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Purchased At</label>
                                            <input type="date" name="purchased_at" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endpush
<script>
$(document).ready(function() {
    $('#mess-itemsTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true
    });
});
</script>
<script>
$(document).ready(function () {

    // ---------------- EDIT BUTTON CLICK ----------------
    $(document).on('click', '.edit-btn', function () {

        let row = $(this).closest('tr');

        let id = row.find('td:eq(0)').text();
        let ingredient = row.find('td:eq(1)').text();
        let qty = row.find('td:eq(2)').text();
        let unit = row.find('td:eq(3)').text();
        let price = row.find('td:eq(4)').text();
        let total = row.find('td:eq(5)').text();
        let by = row.find('td:eq(6)').text();
        let at = row.find('td:eq(7)').text();

        $('#editForm').attr('action', '/mess_items_purchases/' + id);

        $('#editModalBody').html(`
            <div id="edit-purchase-items-container">

                <div class="purchase-item row mb-3 border p-2">
                    <div class="col-md-4">
                        <label>Mess Item Name</label>
                        <input type="text" name="purchases[0][ingredient_name]" class="form-control" value="${ingredient}" required>
                    </div>
                    <div class="col-md-2">
                        <label>Quantity</label>
                        <input type="number" name="purchases[0][quantity]" class="form-control quantity" value="${qty}" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <label>Unit</label>
                        <input type="text" name="purchases[0][unit]" class="form-control" value="${unit}" required>
                    </div>
                    <div class="col-md-2">
                        <label>Price Per Unit</label>
                        <input type="number" name="purchases[0][price_per_unit]" class="form-control price-per-unit" value="${price}" step="0.01" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-success btn-sm edit-add">+</button>
                    </div>
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Total Cost</label>
                    <input type="number" name="total_cost" class="form-control total-cost" value="${total}" readonly>
                </div>
                <div class="col-md-4">
                    <label>Purchased By</label>
                    <input type="text" name="purchased_by" class="form-control" value="${by}">
                </div>
                <div class="col-md-4">
                    <label>Purchased At</label>
                    <input type="date" name="purchased_at" class="form-control" value="${at}">
                </div>
            </div>
        `);
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });

    // ---------------- ADD ROW (OOPER ADD HOGI) ----------------
    $(document).on('click', '.edit-add', function () {

        let container = $('#edit-purchase-items-container');
        let index = container.find('.purchase-item').length;

        let newRow = `
            <div class="purchase-item row mb-3 border p-2">
                <div class="col-md-4">
                    <label>Mess Item Name</label>
                    <input type="text" name="purchases[${index}][ingredient_name]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Quantity</label>
                    <input type="number" name="purchases[${index}][quantity]" class="form-control quantity" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <label>Unit</label>
                    <input type="text" name="purchases[${index}][unit]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Price Per Unit</label>
                    <input type="number" name="purchases[${index}][price_per_unit]" class="form-control price-per-unit" step="0.01" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm edit-remove">−</button>
                </div>
            </div>
        `;
        container.append(newRow);
    });

    // ---------------- REMOVE ROW ----------------
    $(document).on('click', '.edit-remove', function () {
        $(this).closest('.purchase-item').remove();
        calculateEditTotal();
    });

    // ---------------- CALCULATE TOTAL ----------------
    $(document).on('input', '.quantity, .price-per-unit', function () {
        calculateEditTotal();
    });

    function calculateEditTotal() {
        let total = 0;
        $('#edit-purchase-items-container .purchase-item').each(function () {
            let q = parseFloat($(this).find('.quantity').val()) || 0;
            let p = parseFloat($(this).find('.price-per-unit').val()) || 0;
            total += q * p;
        });
        $('#editForm .total-cost').val(total.toFixed(2));
    }

});
</script>
<script>
let createIndex = 1;

// ADD ROW
$(document).on('click', '.add-item', function () {

    let row = `

    <div class="purchase-item row mb-3 border p-2">
        <div class="col-md-4">
            <label>Mess Item Name</label>
            <input type="text" name="purchases[${createIndex}][ingredient_name]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Quantity</label>
            <input type="number" name="purchases[${createIndex}][quantity]" class="form-control quantity" step="0.01" required>
        </div>
        <div class="col-md-2">
            <label>Unit</label>
            <input type="text" name="purchases[${createIndex}][unit]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Price Per Unit</label>
            <input type="number" name="purchases[${createIndex}][price_per_unit]" class="form-control price-per-unit" step="0.01" required>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-item">−</button>
        </div>
    </div>
    `;

    $('#purchase-items').append(row);
    createIndex++;
});

// REMOVE
$(document).on('click', '.remove-item', function () {
    $(this).closest('.purchase-item').remove();
    calculateCreateTotal();
});

// CALCULATE
$(document).on('input', '#purchase-items .quantity, #purchase-items .price-per-unit', function () {
    calculateCreateTotal();
});

function calculateCreateTotal() {
    let total = 0;
    $('#purchase-items .purchase-item').each(function () {
        let q = parseFloat($(this).find('.quantity').val()) || 0;
        let p = parseFloat($(this).find('.price-per-unit').val()) || 0;
        total += q * p;
    });
    $('#createModal .total-cost').val(total.toFixed(2));
}
</script>


@endsection
