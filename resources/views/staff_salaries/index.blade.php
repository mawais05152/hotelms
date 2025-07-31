@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Staff Salaries</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('staff-salaries.create') }}" class="btn btn-primary mb-3">+ Add Salary</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Staff Name</th>
                <th>Salary Amount</th>
                <th>Salary Month</th>
                <th>Paid Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $salary)
                <tr>
                    <td>{{ $salary->id }}</td>
                    <td>{{ $salary->user->name }}</td>
                    <td>{{ $salary->salary_amount }}</td>
                    <td>{{ $salary->salary_month }}</td>
                    <td>{{ $salary->paid_date }}</td>
                    <td>{{ $salary->status }}</td>
                    <td>
                        <a href="{{ route('staff-salaries.edit', $salary->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('staff-salaries.destroy', $salary->id) }}" method="POST" style="display:inline-block;">
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
