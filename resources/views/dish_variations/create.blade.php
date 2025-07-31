@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Add New Dish Variation</h4>
    <form method="POST" action="{{ route('dish_variations.store') }}">
        @csrf
        <div class="mb-3">
            <label>Dish</label>
            <select name="mess_menu_id" class="form-control">
                <option value="">Select Dish</option>
                @foreach($dishes as $dish)
                    <option value="{{ $dish->id }}">{{ $dish->meal_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Variation Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g., Half, Full" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('dish_variations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
