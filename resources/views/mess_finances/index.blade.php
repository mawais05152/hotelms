@extends('layouts.master')

@section('content')
<div class="container">
    <h2 class="mb-4">Mess Finances</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Create Form --}}
  <!-- Modal -->
<div class="modal fade" id="addFinanceModal" tabindex="-1" aria-labelledby="addFinanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Large Modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFinanceModalLabel">Add Finance Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="{{ route('mess-finances.store') }}">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Mess Name</label>
              <select name="mess_meal_id" class="form-control" required>
                @foreach(\App\Models\MessMenu::all() as $menu)
                  <option value="{{ $menu->id }}">{{ $menu->meal_name }} ({{ $menu->date }})</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label>Total Cost</label>
              <input type="number" step="0.01" name="total_cost" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label>Price/Person</label>
              <input type="number" step="0.01" name="price_per_person" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label>Person Served</label>
              <input type="number" name="persons_served" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label>Total Income</label>
              <input type="number" step="0.01" name="total_income" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label>Profit/Loss</label>
              <input type="text" name="profit_or_loss" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Record</button>
        </div>
      </form>
    </div>
  </div>
</div>
    {{-- Table --}}
    <table class="table table-striped table-bordered">
        <!-- Button to open modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addFinanceModal">
            + Add Finance Record
        </button>
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Mess Name</th>
                <th>Total Cost</th>
                <th>Price/Person</th>
                <th>Person Served</th>
                <th>Income</th>
                <th>Profit/Loss</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finances as $finance)
                <tr>
                    <td>{{ $finance->id }}</td>
                    <td>{{ $finance->messMenu->meal_name ?? 'N/A' }} ({{ $finance->messMenu->date ?? '' }})</td>
                    <td>{{ $finance->total_cost }}</td>
                    <td>{{ $finance->price_per_person }}</td>
                    <td>{{ $finance->persons_served }}</td>
                    <td>{{ $finance->total_income }}</td>
                    <td>{{ $finance->profit_or_loss }}</td>
                    <td class="d-flex gap-1">
                        <!-- Edit Button -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $finance->id }}">
                            Edit
                        </button>
                        {{-- You can add modal edit here or delete --}}
                        <form method="POST" action="{{ route('mess-finances.destroy', $finance->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@foreach ($finances as $finance)
<!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $finance->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $finance->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $finance->id }}">Edit Finance Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('mess-finances.update', $finance->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Mess Name</label>
                                <select name="mess_meal_id" class="form-control" required>
                                    @foreach(\App\Models\MessMenu::all() as $menu)
                                        <option value="{{ $menu->id }}" {{ $finance->mess_meal_id == $menu->id ? 'selected' : '' }}>
                                            {{ $menu->meal_name }} ({{ $menu->date }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Total Cost</label>
                                <input type="number" step="0.01" name="total_cost" class="form-control" value="{{ $finance->total_cost }}" required>
                            </div>

                            <div class="col-md-6">
                                <label>Price/Person</label>
                                <input type="number" step="0.01" name="price_per_person" class="form-control" value="{{ $finance->price_per_person }}" required>
                            </div>

                            <div class="col-md-6">
                                <label>Person Served</label>
                                <input type="number" name="persons_served" class="form-control" value="{{ $finance->persons_served }}" required>
                            </div>

                            <div class="col-md-6">
                                <label>Total Income</label>
                                <input type="number" step="0.01" name="total_income" class="form-control" value="{{ $finance->total_income }}" required>
                            </div>

                            <div class="col-md-6">
                                <label>Profit/Loss</label>
                                <input type="text" name="profit_or_loss" class="form-control" value="{{ $finance->profit_or_loss }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
