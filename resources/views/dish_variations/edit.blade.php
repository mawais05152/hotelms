@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Edit Dish Variation</h4>
    <form method="POST" action="{{ route('dish_variations.update', $dishVariation->id) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Dish</label>
            <select name="mess_menu_id" class="form-control">
                <option value="">Select Dish</option>
                @foreach($dishes as $dish)
                    <option value="{{ $dish->id }}" {{ $dish->id == $dishVariation->mess_menu_id ? 'selected' : '' }}>{{ $dish->meal_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Variation Name</label>
            <input type="text" name="name" class="form-control" value="{{ $dishVariation->name }}" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" value="{{ $dishVariation->price }}" step="0.01" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('dish_variations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
