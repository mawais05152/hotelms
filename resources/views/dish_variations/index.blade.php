@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Dish Variations</h4>
        <a href="{{ route('dish_variations.create') }}" class="btn btn-primary">Add New</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Dish</th>
                <th>Variation</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dishVariations as $variation)
                <tr>
                    <td>{{ $variation->id }}</td>
                    <td>{{ $variation->dish->meal_name ?? '-' }}</td>
                    <td>{{ $variation->name }}</td>
                    <td>{{ $variation->price }}</td>
                    <td>
                        <a href="{{ route('dish_variations.edit', $variation->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('dish_variations.destroy', $variation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
