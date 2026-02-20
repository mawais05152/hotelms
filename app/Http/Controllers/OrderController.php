<?php

namespace App\Http\Controllers;
use App\Events\OrderBooked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Log,Auth};
use Stripe\Exception\CardException;
use Stripe\{Stripe,Card,PaymentIntent,Customer};
use App\Models\{User,BookingTable,Category,Product,MessMenu,StockItem,Variation,Order,OrderStatus};
use App\Notifications\OrderPaidNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index(Request $request, $order_id = null)
    {
        $pendingTableIds = Order::whereIn('status', ['pending', 'taken', 'delivered'])->whereNotNull('booking_id')->pluck('booking_id')->unique()->values()->toArray();
        $categories = Category::get();
        $products = Product::all();
        $variations = Variation::all();
        $dishesCategory = Category::where('name', 'Dishes')->first();
        $dishesCategoryId = $dishesCategory ? $dishesCategory->id : null;
        $orders = Order::with(['bookingTable','orderedBy','deliveredBy','product.variation', 'variation'])->get();
        $tables = BookingTable::all();
        $users = User::all();
        $messMenus = MessMenu::all();

        return view('orders.index', compact('orders','tables','users','categories','products','order_id','pendingTableIds','messMenus','dishesCategoryId','variations'));
    }

    public function storeStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,taken,delivered,completed,cancelled,replaced',
            'delivered_by' => 'nullable|exists:users,id',
        ]);

        $existingStatus = OrderStatus::where('order_id', $order->id)->where('status', $request->status)->first();
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
            'status' => 'required|in:pending,completed,cancelled,replaced',
            'order_by' => 'nullable',
            'delivered_by' => 'nullable',
            'category_id' => 'required',
            'product_id' => 'required|array',
            'variation_id' => 'nullable|array',
            'quantity' => 'required|array',
            'price' => 'required|array',
        ]);

        if (!is_array($request->product_id) || count($request->product_id) == 0) {
            return response()->json(['status' => 'error','message' => 'Please select at least one product.']);
        }

        try {
            $orders = [];
            DB::transaction(function () use ($request, &$orders) {
                foreach ($request->product_id as $i => $productId) {
                    $qty = $request->quantity[$i];
                    $price = $request->price[$i];
                    $variationId = $request->variation_id[$i] ?? null;
                    $stock = StockItem::where('product_id', $productId)->where('variation_id', $variationId)->first();
                    if (!$stock || $stock->available_qty < $qty) {
                        throw new \Exception('Stock not available for product: ' . $productId);
                    }
                    $orders[] = Order::create([
                        'booking_id'   => $request->booking_id,
                        'person'       => $request->person,
                        'category_id'  => $request->category_id,
                        'product_id'   => $productId,
                        'variation_id' => $variationId,
                        'quantity'     => $qty,
                        'price'        => $price / $qty,
                        'sub_total'    => $price,
                        'order_type'   => $request->order_type,
                        'order_by'     => $request->order_by,
                        'delivered_by' => $request->delivered_by,
                        'date'         => $request->date,
                        'time'         => $request->time,
                        'status'       => $request->status,
                    ]);
                    $stock->available_qty -= $qty;
                    $stock->total_quantity -= $qty;
                    $stock->save();
                }
            });
             foreach ($orders as $order) {
                event(new OrderBooked($order));
            }
            // return redirect()->route('orders.index')->with('success', 'Order created successfully.');
            return response()->json(['status' => 'success','message' => 'Order created successfully.','orders' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error','message' => $e->getMessage()]);
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
        $users = User::all();
        $order = Order::findorFail($id);
        return view('orders.show',compact('order','users'));
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

        $order = Order::findOrFail($order->id);
        $order->booking_id = $request->booking_id;
        $order->person = $request->person;
        $order->order_type = $request->order_type;
        $order->time = $request->time;
        $order->date = $request->date;
        $order->status = $request->status;
        $order->order_by = $request->order_by;
        $order->delivered_by = $request->delivered_by;
        $order->product_id = json_encode($request->product_id);
        $order->quantity = json_encode($request->quantity);
        $order->price = json_encode($request->price);
        $order->sub_total = array_sum($request->price);
        $order->save();
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
    public function print(Request $request)
    {
        $order = Order::with(['bookingTable', 'orderedBy', 'deliveredBy', 'orderItems.product.category'])->findOrFail($request->order_id);
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
        $orderAmount = $order->sub_total ?? 0;

        $request->validate([
            'payment_method' => 'required',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $customer = Customer::create([
                'name' => $request->card_holder_name,
            ]);
            \Stripe\PaymentMethod::retrieve($request->payment_method)->attach([
                'customer' => $customer->id,
            ]);
            $customer->invoice_settings = ['default_payment_method' => $request->payment_method];
            $customer->save();

            $paymentIntent = PaymentIntent::create([
                'amount' => $orderAmount * 100,
                'currency' => 'usd',
                'customer' => $customer->id,
                'payment_method' => $request->payment_method,
                'off_session' => true,
                'confirm' => true,
                'description' => 'Order Payment',
            ]);
            $order->update(['payment_status' => 'paid']);

            // Notify Admin
            $admin = User::role('Admin')->first();
            if ($admin) {
                try {
                    $admin->notify(new OrderPaidNotification($order));
                } catch (\Exception $e) {
                    Log::error('Notification failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('orders.index')->with('success', 'Payment successful!');
        } catch (CardException $e) {
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function checkStock($product_id, $variation_id = null)
    {
        $stock = StockItem::where('product_id', $product_id)->when($variation_id, fn($q) => $q->where('variation_id', $variation_id))->first();
        return response()->json(['stock' => $stock->balance ?? 0]);
    }
}
