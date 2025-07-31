<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use App\Models\MessItemsPurchase;
use Illuminate\Support\Facades\DB;

class MessItemsPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
    {
        $purchases = MessItemsPurchase::latest()->get();
        return view('mess_items_purchases.index', compact('purchases'));
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
        $request->validate([
            'purchases' => 'required|array|min:1',
            'purchases.*.ingredient_name' => 'required|string',
            'purchases.*.quantity' => 'required|numeric',
            'purchases.*.unit' => 'required|string',
            'purchases.*.price_per_unit' => 'required|numeric',
            'purchased_by' => 'required|string',
            'purchased_at' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->purchases as $item) {
                $total_cost = $item['quantity'] * $item['price_per_unit'];

                $purchase = new MessItemsPurchase();
                // dd($request->all());
                $purchase->ingredient_name = $item['ingredient_name'];
                $purchase->quantity = $item['quantity'];
                $purchase->unit = $item['unit'];
                $purchase->price_per_unit = $item['price_per_unit'];
                $purchase->total_cost = $total_cost;
                $purchase->purchased_by = $request->purchased_by;
                $purchase->purchased_at = $request->purchased_at;
                $purchase->save();

                $stock = StockItem::where('item_type', 'mess')
                    ->where('name', $item['ingredient_name'])
                    ->first();

                if ($stock) {
                    $stock->total_quantity += $item['quantity'];
                    $stock->available_qty += $item['quantity'];
                    $stock->price = $item['price_per_unit'];
                    $stock->total_cost += $item['price_per_unit'] * $item['quantity'];
                    $stock->save();
                } else {
                    $stock = new StockItem();
                    $stock->item_type = 'mess';
                    $stock->name = $item['ingredient_name'];
                    $stock->product_id = null;
                    $stock->asset_id = null;
                    $stock->total_quantity = $item['quantity'];
                    $stock->available_qty = $item['quantity'];
                    $stock->damaged_quantity = 0;
                    $stock->price = $item['price_per_unit'];
                    $stock->unit = $item['unit'];
                    $stock->total_cost += $item['price_per_unit'] * $item['quantity'];
                    $stock->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Items purchased and stock updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MessItemsPurchase $messItemsPurchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessItemsPurchase $messItemsPurchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
    {
        $request->validate([
            'ingredient_name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required',
            'price_per_unit' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'purchased_by' => 'required',
            'purchased_at' => 'required|date',
        ]);

        $purchase = MessItemsPurchase::findOrFail($id);
        $purchase->ingredient_name = $request->ingredient_name;
        $purchase->quantity = $request->quantity;
        $purchase->unit = $request->unit;
        $purchase->price_per_unit = $request->price_per_unit;
        $purchase->total_cost = $request->total_cost;
        $purchase->purchased_by = $request->purchased_by;
        $purchase->purchased_at = $request->purchased_at;
        $purchase->save();

        return redirect()->back()->with('success', 'Purchase updated successfully.');
    }

    public function destroy($id)
    {
        MessItemsPurchase::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Purchase deleted.');
    }
}


