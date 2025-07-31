@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>Variation List</span>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createModal">+ Add
                            New</button>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Item Name</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($variations as $variation)
                                    <tr>
                                        <td>{{ $variation->id }}</td>
                                        <td>{{ $variation->product->name ?? 'N/A' }}</td>
                                        <td>{{ $variation->unit }}</td>
                                        <td>{{ $variation->size }}</td>
                                        <td>{{ $variation->price }}</td>
                                        <td>{{ $variation->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $variation->id }}">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No variations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('variations.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createModalLabel">Add Variation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="product_id">Products</label>
                            <select name="product_id" class="form-control">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Unit</label>
                            <input type="text" name="unit" class="form-control" placeholder="e.g. bottle" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Size</label>
                            <input type="text" name="size" class="form-control" placeholder="e.g. 500ml" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Pice</label>
                            <input type="number" name="price" class="form-control" placeholder="e.g. 1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODALS -->
    @foreach ($variations as $variation)
        <div class="modal fade" id="editModal{{ $variation->id }}" tabindex="-1"
            aria-labelledby="editModalLabel{{ $variation->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('variations.update', $variation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="editModalLabel{{ $variation->id }}">Edit Variation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="product_id">Products</label>
                                <select name="product_id" class="form-control" required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $variation->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Unit</label>
                                <input type="text" name="unit" class="form-control" value="{{ $variation->unit }}"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Size</label>
                                <input type="text" name="size" class="form-control" value="{{ $variation->size }}"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" value="{{ $variation->price }}"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
