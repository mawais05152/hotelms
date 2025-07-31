<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function productSalesReport()
    {
        $salesReport = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity_sold'),
                DB::raw('SUM(quantity * products.price) as total_sales_amount')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->with('product')
            ->get();

        return view('reports.product_sales', compact('salesReport'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
