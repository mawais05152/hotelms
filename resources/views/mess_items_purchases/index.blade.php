@extends('layouts.master')

@section('content')
<div class="container">
    <h4>Mess Items Purchases</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Purchase</button> --}}
    {{-- <div class="card-header">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Purchase</button>
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3 text-end pt-2">
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Search by name, type, category..." onkeyup="searchTable()">
            </div>
        </div>
    </div> --}}
    <div class="card-header">
     <div class="row align-items-center">
        <div class="col-md-6">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                Add Purchase
            </button>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3 text-end">
            <input type="text" id="searchInput" class="form-control"
                placeholder="Search by name, type, category..." onkeyup="searchTable()">
        </div>
    </div>
 </div>

    <table class="table table-bordered table-striped table-hover mt-3">
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->ingredient_name }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>{{ $purchase->unit }}</td>
                <td>{{ $purchase->price_per_unit }}</td>
                <td>{{ $purchase->total_cost }}</td>
                <td>{{ $purchase->purchased_by }}</td>
                <td>{{ $purchase->purchased_at }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $purchase->id }}">Edit</button>
                    <form action="{{ route('mess_items_purchases.destroy', $purchase->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $purchase->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('mess_items_purchases.update', $purchase->id) }}" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Purchase</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('mess_items_purchases.form', ['purchase' => $purchase])
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
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
                            <input type="text" name="purchases[0][ingredient_name]" class="form-control" required>
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

<!-- Create Modal -->
{{-- <div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"> <!-- Increased width -->
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
        <input type="text" name="purchases[0][ingredient_name]" class="form-control" required>
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
                    <input type="number" name="purchases[0][total_cost]" class="form-control total-cost" step="0.01" readonly>
                </div> --}}
                    {{-- <div class="col-md-4">
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
</div> --}}

@endsection
<!-- ✅ jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ Bootstrap JS (if needed) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let itemIndex = 1; // For dynamic naming

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('add-item')) {
        e.preventDefault();
        const itemContainer = document.getElementById('purchase-items');
        const newItem = document.createElement('div');
        newItem.classList.add('purchase-item', 'row', 'mb-3', 'border', 'p-2');

        newItem.innerHTML = `
            <div class="col-md-4">
                <label>Mess Item Name</label>
                <input type="text" name="purchases[${itemIndex}][ingredient_name]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label>Quantity</label>
                <input type="number" name="purchases[${itemIndex}][quantity]" class="form-control quantity" step="0.01" required>
            </div>
            <div class="col-md-2">
                <label>Unit</label>
                <input type="text" name="purchases[${itemIndex}][unit]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label>Price Per Unit</label>
                <input type="number" name="purchases[${itemIndex}][price_per_unit]" class="form-control price-per-unit" step="0.01" required>
            </div>
            <div class="col-md-1 d-flex align-items-end pb-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">-</button>
            </div>
        `;
        itemContainer.appendChild(newItem);
        itemIndex++;
    }

    // Remove item
    if (e.target.classList.contains('remove-item')) {
        e.preventDefault();
        e.target.closest('.purchase-item').remove();
        updateTotalCost(); // Update total after removing
    }
});
</script>

{{-- <script>
    let purchaseIndex = 1;

    $(document).on('click', '.add-item', function () {
        let newItem = `
        <div class="purchase-item row mb-3 border p-2">
            <div class="col-md-4">
                <label>Mess Purchase Name</label>
                <input type="text" name="purchases[${purchaseIndex}][ingredient_name]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label>Quantity</label>
                <input type="number" name="purchases[${purchaseIndex}][quantity]" class="form-control" step="0.01" required>
            </div>

            <div class="col-md-2">
                <label>Unit</label>
                <input type="text" name="purchases[${purchaseIndex}][unit]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label>Price Per Unit</label>
                <input type="number" name="purchases[${purchaseIndex}][price_per_unit]" class="form-control" step="0.01" required>
            </div>

            <div class="col-md-2 d-flex align-items-end pb-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">−</button>
            </div>
        </div>`;

        $('#purchase-items').append(newItem);
        purchaseIndex++;
    });

    $(document).on('click', '.remove-item', function () {
        if ($('.purchase-item').length > 1) {
            $(this).closest('.purchase-item').remove();
        }
    });
</script> --}}
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
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('quantity') || e.target.classList.contains('price-per-unit')) {
        updateTotalCost();
    }
});

function updateTotalCost() {
    let total = 0;
    document.querySelectorAll('#purchase-items .purchase-item').forEach(function (item) {
        const qty = parseFloat(item.querySelector('.quantity')?.value || 0);
        const price = parseFloat(item.querySelector('.price-per-unit')?.value || 0);
        total += qty * price;
    });
    document.querySelector('.total-cost').value = total.toFixed(2);
}
</script>

