@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Product Sales Report</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesReport as $report)
                <tr>
                    <td>{{ $report->product->name ?? 'N/A' }}</td>
                    <td>{{ $report->total_quantity_sold }}</td>
                    <td>{{ number_format($report->total_sales_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
