<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $payment = new Payment();
        $payment->order_id = $request->order_id;
        $payment->sab_price = $request->sab_price;
        $payment->sab_price = $request->sab_price;
        $payment->payment_method = $request->payment_method;
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }
    public function edit($id)
{
    $payment = Payment::findOrFail($id);
    return redirect()->route('payments.index')->with('payment', $payment);
}


    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'order_id' => 'required|numeric',
            'sab_price' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $payment->update([
            'order_id' => $request->order_id,
            'sab_price' => $request->sab_price,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }


    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

   public function orderitem($id)
{
    $orders = OrderItem::with('product')->where('order_id', $id)->get();
    $itemsText = "";
    $totalPrice = 0;

    foreach ($orders as $item) {
        if ($item->product) {
            $productTotal = $item->price * $item->quantity;
            $itemsText .= $item->product->name . " Price = " . number_format($item->price, 2) . " x " . $item->quantity . " = " . number_format($productTotal, 2) . "\n";
            $totalPrice += $productTotal;
        }
    }

    return response()->json([
        'order_items' => $itemsText,
        'total_price' => number_format($totalPrice, 2)
    ]);
}


}
