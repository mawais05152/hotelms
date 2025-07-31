@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <h5>Customer Feedback</h5>
                    <a href="{{ route('customer-feedback.create') }}" class="btn btn-light btn-sm">+ Add Feedback</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-stripped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Feedback</th>
                                <th>Rating</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($feedbacks as $feedback)
                                <tr>
                                    <td>{{ $feedback->id }}</td>
                                    <td>{{ $feedback->order_id }}</td>
                                    <td>{{ $feedback->feedback_text }}</td>
                                    <td>{{ $feedback->rating }}</td>
                                    <td>
                                        <a href="{{ route('customer-feedback.edit', $feedback->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('customer-feedback.destroy', $feedback->id) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
