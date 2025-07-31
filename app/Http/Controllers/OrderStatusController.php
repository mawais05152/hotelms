<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index($orderId)
{
    $order = Order::with('deliveredBy')->findOrFail($orderId);
    $statuses = OrderStatus::with('deliveredBy')
        ->where('order_id', $orderId)
        ->orderBy('updated_at', 'asc')
        ->get();

    return view('order_status.index', compact('order', 'statuses'));
}




     public function create()
    {
        //
    }


    public function show($orderId)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderStatus $orderStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderStatus $orderStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderStatus $orderStatus)
    {
        //
    }
}
