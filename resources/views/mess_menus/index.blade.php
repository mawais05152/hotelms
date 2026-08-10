@extends('layouts.master')
@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4>Mess Menu List</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Menu</button>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <table class="table table-striped table-bordered" id="messmenueTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Mess Name</th>
                                    <th>Date</th>
                                    <th>Cooked By</th>
                                    <th>Persons Served</th>
                                    <th>Qty Made</th>
                                    <th>Qty Available</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $menu->meal_name }}</td>
                                        <td>{{ $menu->date }}</td>
                                        <td>{{ $menu->cooked_by }}</td>
                                        <td>{{ $menu->cooked_for_persons }}</td>
                                        <td>{{ $menu->quantity_made }}</td>
                                        <td>{{ $menu->available_quantity }}</td>
                                        <td>
                                            <!-- Edit -->
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $menu->id }}">Edit</button>

                                            <!-- Delete -->
                                            <form action="{{ route('mess_menus.destroy', $menu->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this meal?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $menu->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('mess_menus.update', $menu->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h5>Edit Meal</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="text" name="meal_name" class="form-control mb-2" value="{{ $menu->meal_name }}" required placeholder="Meal Name">
                                                        <input type="date" name="date" class="form-control mb-2" value="{{ $menu->date }}" required>
                                                        <input type="text" name="cooked_by" class="form-control mb-2" value="{{ $menu->cooked_by }}" required placeholder="Cooked By">
                                                        <input type="number" name="cooked_for_persons" class="form-control mb-2" value="{{ $menu->cooked_for_persons }}" required placeholder="Persons Served">
                                                        <input type="text" name="quantity_made" class="form-control" value="{{ $menu->quantity_made }}" required placeholder="Quantity Made">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary">Update</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
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
        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('mess_menus.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5>Add New Dishes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="meal_name">Dish Name</label>
                                        <input type="text" name="meal_name" class="form-control mb-2" required placeholder="Meal Name">
                                    </div>
                                </div>

                                    <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="cooked_by">Cooked By</label>
                                        <input type="text" name="cooked_by" class="form-control mb-2" required placeholder="Cooked By">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="cooked_for_persons">Persons Served</label>
                                        <input type="number" name="cooked_for_persons" class="form-control mb-2" required placeholder="Persons Served">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="quantity_made">Quantity Made</label>
                                        <input type="text" name="quantity_made" class="form-control" required placeholder="Quantity Made">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="date">Date</label>
                                <input type="date" name="date" class="form-control mb-2" required>
                            </div>
                            <!-- Ingredients Section -->
                            <hr>
                            <h6>Material Used</h6>
                            <div id="ingredient-container">
                                <div class="row mb-2 ingredient-row">
                                    <div class="col-md-4">
                                        <label>Material Use Name</label>
                                        <select name="ingredient_name" class="form-control" required>
                                            <option value="">-- Select Material --</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->ingredient_name }}">
                                                    {{ $material->ingredient_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Quantity</label>
                                        <select name="quantity_used" class="form-control" required>
                                            <option value="">-- Select Material --</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->quantity_used }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Unit</label>
                                        <select name="unit" class="form-control" required>
                                            <option value="">-- Select Unit --</option>
                                            @foreach ($uniqueUnits as $unit)
                                                <option value="{{ $unit }}">{{ $unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-success btn-sm add-row">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endpush
<script>
$(document).ready(function() {
    $('#messmenueTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true
    });
});
</script>
<script>
    let ingredientIndex = 1;

    $(document).on('click', '.add-row', function() {
        const row = `
            <div class="row mb-2 ingredient-row">
                <div class="col-md-4 mb-2">
                    <label>Material Use Name</label>
                    <select name="material_id" class="form-control" required>
                        <option value="">-- Select Material --</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->ingredient_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Quantity</label>
                    <select name="quantity_used" class="form-control" required>
                        <option value="">-- Select Material --</option>
                      @foreach ($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->quantity_used }}</option>
                        @endforeach
                    </select>
                </div>
               <div class="col-md-3">
                    <label>Unit</label>
                    <select name="ingredients[0][unit]" class="form-control unit-select" required>
                        <option value="">-- Select Unit --</option>
                        @foreach ($uniqueUnits as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end pb-2">
                    <button type="button" class="btn btn-danger btn-sm remove-row">-</button>
                </div>
            </div>`;
        $('#ingredient-container').append(row);
        ingredientIndex++;
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('.ingredient-row').remove();
    });
</script>
