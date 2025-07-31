@extends('layouts.master')

@section('content')
<div class="container">
    <h4 class="mb-4">Menu Materials</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Material</button>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        {{-- <th>Mess Name</th> --}}
                        <th>Matrial Use Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        {{-- <td>{{ $item->messMenu->meal_name ?? 'N/A' }}</td> --}}
                        <td>{{ $item->ingredient_name }}</td>
                        <td>{{ $item->quantity_used }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">Edit</button>

                            <form action="{{ route('menu-materials.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>

                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('menu-materials.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Material</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- <div class="mb-2">
                                                    <label>Mess</label>
                                                    <select name="mess_meal_id" class="form-control" required>
                                                        <option value="">Select Mess</option>
                                                        @foreach($menus as $menu)
                                                            <option value="{{ $menu->id }}" {{ $item->mess_meal_id == $menu->id ? 'selected' : '' }}>{{ $menu->meal_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}
                                                <div class="mb-2">
                                                    <label>Matrial Use Name</label>
                                                    <input type="text" name="ingredient_name" value="{{ $item->ingredient_name }}" class="form-control" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Quantity</label>
                                                    <input type="number" step="0.01" name="quantity_used" value="{{ $item->quantity_used }}" class="form-control" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Unit</label>
                                                    <input type="text" name="unit" value="{{ $item->unit }}" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-success">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('menu-materials.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="mb-2">
                        <label>Mess</label>
                        <select name="mess_meal_id" class="form-control" required>
                            <option value="">Select Mess</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->meal_name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="mb-2">
                        <label>Matirial Use Name</label>
                        <input type="text" name="ingredient_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Quantity</label>
                        <input type="number" step="0.01" name="quantity_used" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Unit</label>
                        <input type="text" name="unit" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Bootstrap CSS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap JS (must be after jQuery if used) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
