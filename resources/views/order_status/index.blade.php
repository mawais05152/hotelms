@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Order History for Order #{{ $order->id }}</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Status</th>
                <th>Delivered By</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($statuses as $index => $status)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $status->status }}</td>
                    <td>{{ $status->deliveredBy->name ?? 'N/A' }}</td>
                    <td>{{ optional($status->updated_at)->format('Y-m-d') ?? 'N/A' }}</td>
                    <td>{{ optional($status->updated_at)->format('H:i:s') ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
