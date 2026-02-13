<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderStatusController extends Controller
{
    public function index($orderId)
    {
        $order = Order::with('deliveredBy')->findOrFail($orderId);
        $statuses = OrderStatus::with('deliveredBy')->where('order_id', $orderId)->orderBy('updated_at', 'asc')->get();
        return view('order_status.index', compact('order', 'statuses'));
    }
}
