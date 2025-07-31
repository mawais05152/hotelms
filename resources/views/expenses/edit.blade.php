@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Expense</h4>

                        <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="formgroup mb-3">
                                    <label>Expense Type</label>
                                    <input type="text" name="expense_type" value="{{ $expense->expense_type }}"
                                        class="form-control" required>
                                </div>

                                <div class="formgroup mb-3">
                                    <label>Amount</label>
                                    <input type="number" name="amount" step="0.01" value="{{ $expense->amount }}"
                                        class="form-control" required>
                                </div>

                                <div class="formgroup mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control">{{ $expense->description }}</textarea>
                                </div>

                                <div class="formgroup mb-3">
                                    <label>Expense Date</label>
                                    <input type="date" name="expense_date" value="{{ $expense->expense_date }}"
                                        class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
