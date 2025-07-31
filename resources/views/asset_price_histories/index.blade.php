@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <h4>Asset Price History</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Supplier</th>
                    <th>Warehouse</th>
                    <th>Note</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="priceHistoryTableBody">
                @foreach($histories as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->new_price }}</td>
                        <td>{{ $item->added_quantity }}</td>
                        <td>{{ $item->supplier_name }}</td>
                        <td>{{ $item->warehouse_name }}</td>
                        <td>{{ $item->note ?? '-' }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
