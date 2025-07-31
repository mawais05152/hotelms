@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Stock Items
                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#stockModal">
                        + Add Stock Item
                    </button>
                </h4>
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3 text-end pt-2">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Search by name, type, category..." onkeyup="searchTable()">
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Item Type</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Total Qty</th>
                            <th>Total Price</th>
                            <th>Damaged Qty</th>
                            <th>Available Qty</th>
                            {{-- <th>Unit</th>
                            <th>Size</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ ucfirst($item->item_type) }}</td>
                                <td>
                                    @if ($item->item_type === 'product' && $item->product)
                                        {{ $item->product->name }}
                                    @elseif ($item->item_type === 'asset' && $item->asset)
                                        {{ $item->asset->name }}
                                    @elseif ($item->item_type === 'mess')
                                        {{ $item->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                {{-- <td>{{ $item->name }}</td> --}}
                                <td>{{ $item->price ?? '-' }}</td>
                                <td>{{ $item->total_quantity }}</td>
                                <td>{{ $item->total_cost }}</td>
                                <td>{{ $item->damaged_quantity }}</td>
                                <td>{{ $item->available_qty }}</td>

                                {{-- <td>{{ $item->product->variation->unit ?? '-' }}</td>
                                <td>{{ $item->product->variation->size ?? '-' }}</td> --}}
                                <td>
                                    <a href="{{ route('stock-items.index', ['edit' => $item->id]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('stock-items.destroy', $item->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    <!-- Variation Modal -->
                                    <a href="javascript:void(0);" onclick="showVariations({{ $item->product_id }})"
                                    class="btn btn-info btn-sm">Show Variations</a>
                                    {{-- <button type="button" class="btn btn-secondary btn-sm printBtn"
                                        data-id="{{ $item->id }}">Print</button> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php
        $editId = request()->query('edit');
        $isEdit = !is_null($editId);
        $editItem = $isEdit ? $stockItems->firstWhere('id', $editId) : null;
    @endphp

    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ $isEdit ? route('stock-items.update', $editItem->id) : route('stock-items.store') }}"
                method="POST" class="modal-content">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Add' }} Stock Item</h5>
                    <a href="{{ route('stock-items.index') }}" class="btn-close"></a>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Item Type</label>
                        <select name="item_type" id="itemType" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="product">Product</option>
                            <option value="asset">Asset</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label> Item Name</label>
                        <select name="item_id" id="itemSelect" class="form-select" required>
                            <option value="">-- Select Item --</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Unit</label>
                        <input type="text" name="unit" id="unitField" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Size</label>
                        <input type="text" name="size" id="sizeField" class="form-control" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Total Qty</label>
                        <input type="number" name="total_quantity" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Damaged Qty</label>
                        <input type="number" name="damaged_quantity" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Available Qty</label>
                        <input type="number" name="available_qty" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update' : 'Save' }}</button>
                    <a href="{{ route('stock-items.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Variation Modal -->
<div class="modal fade" id="variationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Product Variations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="variationBody">
        <!-- Variations will be loaded here -->
      </div>
    </div>
  </div>
</div>



@endsection
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@push('scripts')
    <script>
        function populateItemSelect(type) {
            $('#itemSelect').empty().append('<option value="">-- Select Item --</option>');
            $('#unitField').val('');
            $('#sizeField').val('');
            $('input[name="price"]').val('');

            if (type !== '') {
                $.ajax({
                    url: '/get-items/' + type,
                    type: 'GET',
                    success: function(items) {
                        $.each(items, function(index, item) {
                            $('#itemSelect').append(
                                `<option value="${item.id}"
                            data-unit="${item.unit}"
                            data-size="${item.size}"
                            data-price="${item.price}">
                            ${item.name}
                        </option>`
                            );
                        });
                    },
                    error: function() {
                        alert('Failed to fetch items.');
                    }
                });
            }
        }

        function autoFillFields() {
            var selected = $('#itemSelect').find(':selected');
            var type = $('#itemType').val();
            if (type === 'product') {
                $('#unitField').val(selected.data('unit') || '');
                $('#sizeField').val(selected.data('size') || '');
                $('#unitField').closest('.col-md-6').show();
                $('#sizeField').closest('.col-md-6').show();
            } else {
                $('#unitField').closest('.col-md-6').hide();
                $('#sizeField').closest('.col-md-6').hide();
            }
            $('input[name="price"]').val(selected.data('price') || '');
        }

        $('#itemType').on('change', function() {
            var type = $(this).val();
            populateItemSelect(type);
            if (type === 'product') {
                $('#unitField').closest('.col-md-6').show();
                $('#sizeField').closest('.col-md-6').show();
            } else {
                $('#unitField').closest('.col-md-6').hide();
                $('#sizeField').closest('.col-md-6').hide();
            }
        });

        $('#itemSelect').on('change', function() {
            autoFillFields();
        });
    </script>
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
  function showVariations(productId) {
    $.get(`/products/${productId}/variations`, function(data) {
      $('#variationBody').html(data);
      $('#variationModal').modal('show');
    });
  }
</script>
@endpush
