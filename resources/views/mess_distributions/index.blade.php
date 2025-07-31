@extends('layouts.master')

@section('content')
<div class="container">
    <h4>Mess Distributions</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Distribution</button>

    <!-- Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Mess Name</th>
                <th>Person</th>
                <th>Quantity</th>
                <th>Remarks</th>
                <th>Delivered At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distributions as $distribution)
            <tr>
                <td>{{ $distribution->messMenu->meal_name ?? 'N/A' }}</td>
                <td>{{ $distribution->person_name }}</td>
                <td>{{ $distribution->quantity_given }}</td>
                <td>{{ $distribution->remarks }}</td>
                <td>{{ $distribution->delivered_at }}</td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $distribution->id }}">Edit</button>
                    <!-- Delete -->
                    <form action="{{ route('mess-distributions.destroy', $distribution->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $distribution->id }}">
                <div class="modal-dialog">
                    <form action="{{ route('mess-distributions.update', $distribution->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Edit Distribution</h5></div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Meal</label>
                                    <select name="mess_meal_id" class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}" {{ $distribution->mess_meal_id == $menu->id ? 'selected' : '' }}>
                                                {{ $menu->meal_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Person</label>
                                    <input type="number" name="person_name" class="form-control" value="{{ $distribution->person_name }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Quantity Given</label>
                                    <input type="text" name="quantity_given" class="form-control" value="{{ $distribution->quantity_given }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Remarks</label>
                                    <input type="text" name="remarks" class="form-control" value="{{ $distribution->remarks }}">
                                </div>
                                {{-- <div class="mb-2">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Lunch" {{ $distribution->status == 'Lunch' ? 'selected' : '' }}>Lunch</option>
                                        <option value="Dinner" {{ $distribution->status == 'Dinner' ? 'selected' : '' }}>Dinner</option>
                                        <option value="Staff" {{ $distribution->status == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    </select>
                                </div> --}}

                                <div class="mb-2">
                                    <label>Delivered At</label>
                                    <input type="datetime-local" name="delivered_at" class="form-control" value="{{ \Carbon\Carbon::parse($distribution->delivered_at)->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success">Update</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <form action="{{ route('mess-distributions.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5>Add Distribution</h5></div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Meal</label>
                        <select name="mess_meal_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->meal_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Person Name</label>
                        <input type="text" name="person_name" class="form-control" required>
                    </div>
                    {{-- <div class="mb-2">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Lunch" {{ $distribution->status == 'Lunch' ? 'selected' : '' }}>Lunch</option>
                            <option value="Dinner" {{ $distribution->status == 'Dinner' ? 'selected' : '' }}>Dinner</option>
                            <option value="Staff" {{ $distribution->status == 'Staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div> --}}
                    <div class="mb-2">
                        <label>Quantity Given</label>
                        <input type="text" name="quantity_given" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Remarks</label>
                        <input type="text" name="remarks" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Delivered At</label>
                        <input type="datetime-local" name="delivered_at" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
