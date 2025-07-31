@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Expenses List</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3">+ Add Expense</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Expense Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Expense Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $expense->id }}</td>
                    <td>{{ $expense->expense_type }}</td>
                    <td>{{ $expense->amount }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->expense_date }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
