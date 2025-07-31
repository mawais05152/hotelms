<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\MessMenu;
use App\Models\OrderItem;
use App\Models\StockItem;
use App\Models\Variation;
use App\Models\OrderStatus;
use App\Models\BookingTable;
use Illuminate\Http\Request;
use App\Models\DishVariation;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getProductsByCategory($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category->name == 'Dishes') {
            $dishes = MessMenu::all();
            $data = [];
            foreach ($dishes as $dish) {
                $data[] = [
                    'id' => $dish->id,
                    'meal_name' => $dish->meal_name,
                    'type' => 'Dishes'
                ];
            }
            return response()->json($data);
        } else {
            $products = Product::where('category_id', $categoryId)->get();
            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' => 'product'
                ];
            }
            return response()->json($data);
        }
    }

    public function getDishVariations($dishId)
    {
        $variations = DishVariation::where('mess_menu_id', $dishId)->get();
        $data = [];
        foreach ($variations as $variation) {
            $data[] = [
                'id' => $variation->id,
                'variation_name' => $variation->name,
                'price' => $variation->price
            ];
        }
        return response()->json($data);
    }

    public function getProductVariations($productId)
    {
        $variations = Variation::where('product_id', $productId)->get();
        $data = [];
        foreach ($variations as $variation) {
            $data[] = [
                'id' => $variation->id,
                'unit' => $variation->unit,
                'size' => $variation->size,
                'price' => $variation->price
            ];
        }
        return response()->json($data);
    }

    public function index(Request $request, $order_id = null)
    {
        $pendingTableIds = Order::where('status', 'Pending')->pluck('booking_id')->toArray();

        $categories = Category::all();
        $products = Product::all();

        $orders = Order::with([
            'bookingTable',
            'orderedBy',
            'deliveredBy',
            'orderItems.product.category',
            'messmenu',
            // 'orderItems.variation',
        ])->get();

        $tables = BookingTable::all();
        $users = User::all();
        $messMenus = MessMenu::all();
        return view('orders.index', compact('orders', 'tables', 'users', 'categories', 'products', 'order_id', 'pendingTableIds', 'messMenus'));
    }

    public function storeStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,taken,delivered,completed,cancelled,replaced',
            'delivered_by' => 'nullable|exists:users,id',
        ]);

        $existingStatus = OrderStatus::where('order_id', $order->id)
            ->where('status', $request->status)
            ->first();

        if ($existingStatus) {
            $existingStatus->update([
                'delivered_by' => $request->delivered_by,
                'updated_at' => now(),
            ]);
        } else {
            OrderStatus::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'delivered_by' => $request->delivered_by,
            ]);
        }

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }


    public function create()
    {
        $pendingTableIds = Order::where('status', 'Pending')->pluck('booking_id')->toArray();
        $tables = BookingTable::all();
        $users = User::all();
        $categories = Category::all();
        $products = Product::all();
        return view('orders.create', compact('tables', 'users', 'categories', 'products', 'pendingTableIds'));
    }


//     public function store(Request $request)
//     {
//         $request->validate([
//             'booking_id' => 'required',
//             'person' => 'required',
//             'order_type' => 'required',
//             'status' => 'required',
//             'order_by' => 'nullable',
//             'delivered_by' => 'nullable',
//             'product_id' => 'required|array',
//             'variation_id' => 'nullable|array',
//             'quantity' => 'required|array',
//             'price' => 'required|array',
//         ]);

//         DB::beginTransaction();

//         try {
//             // foreach ($request->product_id as $key => $product_id) {
//             //     $qty = $request->quantity[$key];

//             //     // Check for variation if used
//             //     $variation = Variation::where('product_id', $product_id)->first();
//             //     $stockItem = StockItem::where('product_id', $product_id)->first();

//             //     if (!$stockItem || $stockItem->available_qty < $qty) {
//             //         return redirect()->route('orders.index')->withErrors(['error' => "Insufficient stock for product ID: $product_id"]);
//             //     }
//             // }

//             $order = new Order();
//             dd($request->all());
//             $order->booking_id = $request->booking_id;
//             $order->person = $request->person;
//             $order->order_type = $request->order_type;
//             $order->time = $request->time;
//             $order->date = $request->date;
//             $order->status = $request->status;
//             $order->order_by = $request->order_by;
//             $order->delivered_by = $request->delivered_by;
//             $order->save();

//             foreach ($request->product_id as $key => $product_id) {
//                 $qty = $request->quantity[$key];
//                 $price = $request->price[$key];
//                 $stockItem = StockItem::where('product_id', $product_id)->first();

//                 $orderItem = new OrderItem();
//                 $orderItem->order_id = $order->id;
//                 $orderItem->product_id = $product_id;
//                 $orderItem->category_id = Product::find($product_id)->category_id;
//                 $orderItem->quantity = $qty;
//                 // $orderItem->variation_id = $variation_id;
//                 $variation_id = $request->variation_id[$key] ?? null;
// $orderItem->variation_id = $variation_id;
//                 $orderItem->price = $price;
//                 $orderItem->sub_total = $qty * $price;
//                 $orderItem->save();

//                 $stockItem->total_quantity -= $qty;
//                 $stockItem->available_qty -= $qty;
//                 $stockItem->save();
//             }

//             DB::commit();
//             return redirect()->route('orders.index')->with('success', 'Order saved successfully.');
//         } catch (\Exception $e) {
//             DB::rollback();
//             return back()->withErrors(['error' => 'Failed to save order. ' . $e->getMessage()]);
//         }
//     }

public function store(Request $request)
{
    $request->validate([    
        'booking_id' => 'required',
        'person' => 'required',
        'order_type' => 'required',
        'status' => 'required',
        'order_by' => 'nullable',
        'delivered_by' => 'nullable',
        'product_id' => 'required|array',
        'variation_id' => 'nullable|array',
        'quantity' => 'required|array',
        'price' => 'required|array',
    ]);

    DB::beginTransaction();

    try {
        foreach ($request->product_id as $key => $product_id) {
            $qty = $request->quantity[$key];
            $stockItem = StockItem::where('product_id', $product_id)->first();

            if (!$stockItem || $stockItem->available_qty < $qty) {
                return redirect()->route('orders.index')->withErrors([
                    'error' => "Insufficient stock for product ID: $product_id"
                ]);
            }
        }

        $order = new Order();
        // dd($request->all());
        $order->booking_id = $request->booking_id;
        $order->person = $request->person;
        $order->order_type = $request->order_type;
        $order->time = $request->time;
        $order->date = $request->date;
        $order->status = $request->status;
        $order->order_by = $request->order_by;
        $order->delivered_by = $request->delivered_by;
        $order->save();

        foreach ($request->product_id as $key => $product_id) {
            $qty = $request->quantity[$key];
            $price = $request->price[$key];
            $variation_id = $request->variation_id[$key] ?? null;

            $stockItem = StockItem::where('product_id', $product_id)->first();

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product_id;
            $orderItem->category_id = Product::find($product_id)->category_id;
            $orderItem->variation_id = $variation_id;
            $orderItem->quantity = $qty;
            $orderItem->price = $price;
            $orderItem->sub_total = $qty * $price;
            $orderItem->save();

            $stockItem->total_quantity -= $qty;
            $stockItem->available_qty -= $qty;
            $stockItem->save();
        }

        DB::commit();
        return redirect()->route('orders.index')->with('success', 'Order saved successfully.');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors(['error' => 'Failed to save order. ' . $e->getMessage()]);
    }
}


    public function edit(Order $order)
    {
        $tables = BookingTable::all();
        return view('orders.edit', compact('order', 'tables'));
    }

    public function show($id)
    {
        $order = Order::with(['bookingTable', 'orderItems.product'])->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order_items = $order->orderItems->map(function ($item) {
            return [
                'product_name' => $item->product->name ?? 'N/A',
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        });

        return response()->json([
            'id' => $order->id,
            'date' => $order->date,
            'time' => $order->time,
            'table_number' => $order->bookingTable->table_number ?? 'N/A',
            'order_type' => $order->order_type,
            'status' => $order->status,
            'sub_total' => $order->orderItems->sum('price'),
            'order_items' => $order_items,
        ]);
    }


    public function update(Request $request, Order $order)
    {
        $request->validate([
            'booking_id' => 'required',
            'person' => 'required',
            'order_type' => 'required',
            'status' => 'required',
            'order_by' => 'nullable',
            'delivered_by' => 'nullable',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'price' => 'required|array',
        ]);

        $order->booking_id = $request->booking_id;
        $order->person = $request->person;
        $order->order_type = $request->order_type;
        $order->time = $request->time;
        $order->date = $request->date;
        $order->status = $request->status;
        $order->order_by = $request->order_by;
        $order->delivered_by = $request->delivered_by;
        $order->save();

        OrderItem::where('order_id', $order->id)->delete();

        foreach ($request->product_id as $key => $product_id) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product_id;
            $orderItem->category_id = Product::find($product_id)->category_id;
            $orderItem->quantity = $request->quantity[$key];
            $orderItem->price = $request->price[$key];
            $orderItem->sub_total = $request->sub_total;
            $orderItem->save();
        }
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }


    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
    public function print(Request $request)
    {
        $order = Order::with(['bookingTable', 'orderedBy', 'deliveredBy', 'orderItems.product.category'])
            ->findOrFail($request->order_id);
        return view('orders.print', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        OrderStatus::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Status updated and history saved.');
    }
}
