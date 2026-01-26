<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Order;
use Stripe\PaymentIntent;
use App\Events\OrderPlaced;
use App\Models\OrderStatus;
use App\Models\BookingTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Category, Product, MessMenu, OrderItem, StockItem, Variation, DishVariation};

class OrderController extends Controller
{
    public function index(Request $request, $order_id = null)
    {
        $pendingTableIds = Order::where('status', 'Pending')->pluck('booking_id')->toArray();

        $categories = Category::get();
        $products = Product::all();
        // $dishesCategoryId = Category::where('name', 'Dishes')->first()->id;
        $dishesCategory = Category::where('name', 'Dishes')->first();
        $dishesCategoryId = $dishesCategory ? $dishesCategory->id : null;

        $orders = Order::with([
            'bookingTable',
            'orderedBy',
            'deliveredBy',
            'orderItems.product.category',
            'orderItems.messMenu',
            'orderItems.messMenu.dishVariation',
        ])->get();

        $tables = BookingTable::all();
        $users = User::all();
        $messMenus = MessMenu::all();

        return view('orders.index', compact(
            'orders',
            'tables',
            'users',
            'categories',
            'products',
            'order_id',
            'pendingTableIds',
            'messMenus',
            'dishesCategoryId'
        ));
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
        // ya kya error a raha tha is liye comment kiya tha
        // $order->update([
        //     'status' => $request->status,
        // ]);

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
        $categoryId = $request->category_id;
        $category = Category::find($categoryId);

        DB::beginTransaction();
        try {
            if ($category->name == 'Dishes') {
                foreach ($request->product_id as $key => $product_id) {
                    $qty = $request->quantity[$key] ?? 0;
                    $messMenu = MessMenu::find($product_id);
                    if (!$messMenu) {
                        throw new \Exception("Menu item not found for product ID: $product_id");
                    }

                    $variationId = $request->variation_id[$key] ?? null;
                    if ($variationId) {
                        $dishVariation = DishVariation::where('id', $variationId)
                            ->where('mess_menu_id', $product_id)
                            ->first();
                        if (!$dishVariation) {
                            throw new \Exception("Variation not found for product ID: $product_id");
                        }
                    }
                }

                // Create new order
                $order = new Order();
                $order->booking_id = $request->booking_id;
                $order->person = $request->person;
                $order->order_type = $request->order_type;
                $order->time = $request->time;
                $order->date = $request->date;
                $order->status = $request->status;
                $order->order_by = $request->order_by;
                $order->delivered_by = $request->delivered_by;
                $order->save();

                // Create order items
                foreach ($request->product_id as $key => $product_id) {
                    $qty = $request->quantity[$key] ?? 0;
                    $price = $request->price[$key] ?? 0;
                    $variationId = $request->variation_id[$key] ?? null;

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product_id;
                    $orderItem->category_id = $categoryId;
                    $orderItem->variation_id = $variationId;
                    $orderItem->quantity = $qty;
                    $orderItem->price = $price;
                    $orderItem->sub_total = $qty * $price;
                    $orderItem->save();

                    $messMenu = MessMenu::find($product_id);
                    $messMenu->available_quantity -= $qty;
                    $messMenu->save();
                }
            } else {
                foreach ($request->product_id as $key => $product_id) {
                    $qty = $request->quantity[$key] ?? 0;
                    $stockItem = StockItem::where('product_id', $product_id)->first();

                    if (!$stockItem || $stockItem->available_qty < $qty) {
                        throw new \Exception("Insufficient stock for product ID: $product_id");
                    }
                }

                $order = new Order();
                $order->booking_id = $request->booking_id;
                $order->person = $request->person;
                $order->order_type = $request->order_type;
                $order->time = $request->time;
                $order->date = $request->date;
                $order->status = $request->status;
                $order->order_by = $request->order_by;
                $order->delivered_by = $request->delivered_by;
                $order->save();

                // Create order items and update stock
                foreach ($request->product_id as $key => $product_id) {
                    $qty = $request->quantity[$key] ?? 0;
                    $price = $request->price[$key] ?? 0;
                    $variation_id = $request->variation_id[$key] ?? null;

                    $stockItem = StockItem::where('product_id', $product_id)->first();
                    $stockItem->available_qty -= $qty;
                    $stockItem->save();

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product_id;
                    $orderItem->category_id = Product::find($product_id)->category_id;
                    $orderItem->variation_id = $variation_id;
                    $orderItem->quantity = $qty;
                    $orderItem->price = $price;
                    $orderItem->sub_total = $qty * $price;
                    $orderItem->save();
                }
            }
            //  event(new OrderPlaced($order));
            DB::commit();
            event(new OrderPlaced($order));
            return redirect()->route('orders.index')->with('success', 'Order saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to save order. ' . $e->getMessage()]);
        }
    }

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
        $variations = DishVariation::where('mess_menu_id', $dishId)->select('id','variation_name','price')->get();
        $data = [];
        if(!is_null($variations)) {
            $data = $variations->toArray();
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

    public function processOrders($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.pay.create', compact('order'));
    }

    public function ordersPay(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $orderAmount = $order->orderItems->sum('price');

        $request->validate([
            'payment_method' => 'required',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            \Stripe\PaymentIntent::create([
                'amount' => $orderAmount * 100,
                'currency' => 'usd',
                'description' => 'Order Payment',
                'payment_method' => $request->payment_method,
                'confirm' => true,
                'payment_method_types' => ['card'],
            ]);

            $order->update(['payment_status' => 'paid']);

            return redirect()->route('orders.index')->with('success', 'Payment successful!');
        } catch (CardException $e) {
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
}
