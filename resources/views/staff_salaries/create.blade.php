@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Add Staff Salary</h4>

    <form action="{{ route('staff-salaries.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Staff Member</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select Staff</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Salary Amount</label>
            <input type="number" name="salary_amount" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Salary Month (YYYY-MM)</label>
            <input type="month" name="salary_month" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Paid Date</label>
            <input type="date" name="paid_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('staff-salaries.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
