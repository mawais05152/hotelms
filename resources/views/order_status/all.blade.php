@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>All Orders - Status Histories</h5>
        </div>
        <div class="card-body">
            @foreach($orders as $order)
                <h6 class="text-dark">Order #{{ $order->id }}</h6>
                <table class="table table-bordered mb-4">
                    <thead class="table-dark">
                        <tr>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->statusHistories as $history)
                            <tr>
                                <td>{{ ucfirst($history->status) }}</td>
                                <td>{{ $history->updated_at }}</td>
                                <td>{{ $history->user->name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No status history for this order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>
@endsection
