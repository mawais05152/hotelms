<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class OrderItemController extends Controller
{

public function create(Request $request)
{

    $order_id = $request->order_id;
    $categories = Category::all();
    $products = Product::all();

    return view('order_items.create', compact('order_id', 'categories', 'products'));
}


public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required',
        'product_ids' => 'required|array',
        'price' => 'required|numeric',
        'quantity' => 'required|integer|min:1'
    ]);

    foreach ($request->product_ids as $productId) {
        $subtotal = $request->price * $request->quantity;

        OrderItem::create([
            'order_id' => $request->order_id,
            'product_id' => $productId,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'sub_total' => $subtotal
        ]);
    }
    return response()->json(['success' => true]);
}


public function edit($id)
{
    $item = OrderItem::findOrFail($id);
    $products = Product::with('category')->get();

    return view('order_items.edit', compact('item', 'products'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'product_id' => 'required',
        'category_id' => 'required',
        'price' => 'required|numeric',
        'quantity' => 'required|integer|min:1'
    ]);

    $item = OrderItem::findOrFail($id);
    $item->update([
        'product_id' => $request->product_id,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'quantity' => $request->quantity,
        'sub_total' => $request->price * $request->quantity
    ]);
    return redirect()->route('order-items.index', ['order_id' => $item->order_id])->with('success', 'Item Updated');
}

public function destroy($id)
{
    $item = OrderItem::findOrFail($id);
    $order_id = $item->order_id;
    $item->delete();
    return redirect()->route('order-items.index', ['order_id' => $order_id])->with('success', 'Item Deleted');
}
}
