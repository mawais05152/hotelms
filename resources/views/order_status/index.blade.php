@extends('layouts.master')

@section('content')
<div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Orders History</h4>
            <button class="btn btn-success" id="addOrderBtn">+ Add Order</button>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <input type="text" id="searchInput" class="form-control w-25" style="height: 38px;" placeholder="Search by  Product, Status...">
            </div>
            {{-- <div class="container mt-4">
                <h4>Order History for Order #{{ $order->id }}</h4> --}}
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Delivered By</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statuses as $status)
                        <tr>
                            <td>{{ $status->id }}</td>
                            <td>{{ $status->status }}</td>
                            <td>{{ $status->deliveredBy->name ?? 'N/A' }}</td>
                            <td>{{ optional($status->updated_at)->format('Y-m-d') ?? 'N/A' }}</td>
                            <td>{{ optional($status->updated_at)->format('H:i:s') ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    {{-- </div> --}}
    </div>
</div>
</div>
@endsection
