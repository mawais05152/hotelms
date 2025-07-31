@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Damaged Items
                    <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#damageModal">
                        Add Damaged Item
                    </button>
                </h4>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Item</th>
                            <th>Variation</th>
                            <th>Damaged By</th>
                            <th>Qty</th>
                            <th>Date</th>
                            <th>Fine</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($damagedItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ ucfirst($item->item_type) }}</td>
                                {{-- <pre>{{ dd($item->stockItem) }}</pre> --}}
                                {{-- <pre>{{ dd($item) }}</pre> --}}

                                {{-- <pre>{{ dd($item->toArray()) }}</pre> --}}
                                <td>{{ $item->stockItem->variation->product->name ?? '-' }}</td>
                                <td>{{ $item->variation->unit ?? '-' }} - {{ $item->variation->size ?? '-' }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->damage_date }}</td>
                                <td>{{ $item->fine_amount }}</td>
                                <td>{{ $item->reason }}</td>
                                <td>
                                    <a href="{{ route('damaged_items.index', ['edit' => $item->id]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
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
        $editItem = $isEdit ? $damagedItems->firstWhere('id', $editId) : null;
    @endphp

    <div class="modal fade" id="damageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ $isEdit ? route('damaged_items.update', $editItem->id) : route('damaged_items.store') }}"
                method="POST" class="modal-content">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEdit ? 'Edit' : 'Add' }} Damaged Item</h5>
                    <a href="{{ route('damaged_items.index') }}" class="btn-close"></a>
                </div>
                <input type="hidden" name="stock_item_id" id="stockItemId">
                <input type="hidden" name="variation_id" id="variationId">

                <div class="modal-body row g-3">
                    <div class="col-md-4">
                        <label>Type</label>
                        <select name="item_type" id="damageType" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="product"
                                {{ old('item_type', $editItem->item_type ?? '') == 'product' ? 'selected' : '' }}>Product
                            </option>
                            <option value="asset"
                                {{ old('item_type', $editItem->item_type ?? '') == 'asset' ? 'selected' : '' }}>Asset
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Item</label>
                        <select name="item_id" id="damageItem" class="form-select" required>
                            <option value="">-- Select Item --</option>
                        </select>
                    </div>

                    <div class="col-md-4 variation-fields">
                        <label>Unit</label>
                        <input type="text" name="unit" id="unitField" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 variation-fields">
                        <label>Size</label>
                        <input type="text" name="size" id="sizeField" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Damaged By</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Select User --</option>
                            <option value="0" {{ old('user_id', $editItem->user_id ?? '') == 0 ? 'selected' : '' }}>
                                Unknown</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $editItem->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="{{ $editItem->quantity ?? '' }}" class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        {{-- <label>Damage Date</label>
                        <input type="date" name="damage_date" value="{{ $editItem->damage_date ?? '' }}"
                            class="form-control" required> --}}
                        <label>Damage Date</label>
                        <input type="date" name="damage_date"
                            value="{{ old('damage_date', $editItem->damage_date ?? \Carbon\Carbon::now()->toDateString()) }}"
                            class="form-control" required readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Fine Amount</label>
                        <input type="number" step="0.01" name="fine_amount" value="{{ $editItem->fine_amount ?? '' }}"
                            class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label>Reason</label>
                        <textarea name="reason" class="form-control" rows="2">{{ $editItem->reason ?? '' }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update' : 'Save' }}</button>
                    <a href="{{ route('damaged_items.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@push('scripts')
    <script>
        function loadItems(type) {
            $('#damageItem').empty().append('<option value="">-- Select Item --</option>');
            $('#unitField').val('');
            $('#sizeField').val('');

            if (type !== '') {
                $.get('/get-items/' + type, function(items) {
                    $.each(items, function(i, item) {
                        $('#damageItem').append(
                            `<option value="${item.id}" data-unit="${item.unit}" data-size="${item.size}">${item.name}</option>`
                        );
                    });
                });

                if (type === 'product') {
                    $('.variation-fields').show();
                } else {
                    $('.variation-fields').hide();
                }
            }
        }

        function autoFillVariationFields() {
            const selected = $('#damageItem option:selected');
            $('#unitField').val(selected.data('unit') || '');
            $('#sizeField').val(selected.data('size') || '');
        }

        $('#damageType').on('change', function() {
            const type = $(this).val();
            loadItems(type);
        });

        $('#damageItem').on('change', function() {
            autoFillVariationFields();
        });

        $(document).ready(function() {
            if ($('#damageType').val() !== 'product') {
                $('.variation-fields').hide();
            }
        });
    </script>
@endpush
