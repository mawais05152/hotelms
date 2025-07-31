@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Edit Staff Salary</h4>

    <form action="{{ route('staff-salaries.update', $salary->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Staff Member</label>
            <select name="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $salary->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Salary Amount</label>
            <input type="number" name="salary_amount" step="0.01" value="{{ $salary->salary_amount }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Salary Month (YYYY-MM)</label>
            <input type="month" name="salary_month" value="{{ $salary->salary_month }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Paid Date</label>
            <input type="date" name="paid_date" value="{{ $salary->paid_date }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Pending" {{ $salary->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Paid" {{ $salary->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Cancelled" {{ $salary->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('staff-salaries.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
