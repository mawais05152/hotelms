@extends('layouts.master')
@section('content')
<div class="container mt-4">
    <h3>Invoice: {{ $invoice->invoice_no }}</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Price/Unit</th>
                <th>Total Cost</th>
                <th>Purchased By</th>
                <th>Purchased At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->ingredient_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->price_per_unit }}</td>
                <td>{{ $item->total_cost }}</td>
                <td>{{ $item->purchased_by }}</td>
                <td>{{ $item->purchased_at }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Grand Total:</th>
                <th>{{ $grandTotal }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
