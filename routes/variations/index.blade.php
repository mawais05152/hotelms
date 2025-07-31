@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Variation List
                <!-- Button to trigger Create Modal -->
                <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Variation
                </button>
            </h4>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Unit</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variations as $variation)
                    <tr>
                        <td>{{ $variation->id }}</td>
                        <td>{{ $variation->unit }}</td>
                        <td>{{ $variation->size }}</td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $variation->id }}">
                                Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('variations.destroy', $variation->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $variation->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('variations.update', $variation->id) }}" method="POST" class="modal-content">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Variation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Unit</label>
                                        <input type="text" name="unit" class="form-control" value="{{ $variation->unit }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Size</label>
                                        <input type="text" name="size" class="form-control" value="{{ $variation->size }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

<!-- Create Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('variations.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Variation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Unit</label>
                    <input type="text" name="unit" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Size</label>
                    <input type="text" name="size" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
