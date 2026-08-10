<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use App\Models\MessItemsPurchase;
use Illuminate\Support\Facades\DB;

class MessItemsPurchaseController extends Controller
{

    public function index()
    {
        $purchases = MessItemsPurchase::latest()->get();
        return view('mess_items_purchases.index', compact('purchases'));
    }

    public function create()
    {
        //
    }

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

            $rowTotal = $item['quantity'] * $item['price_per_unit'];

            // -------- SAVE PURCHASE --------
            MessItemsPurchase::create([
                'ingredient_name' => $item['ingredient_name'],
                'quantity'        => $item['quantity'],
                'unit'            => $item['unit'],
                'price_per_unit'  => $item['price_per_unit'],
                'total_cost'      => $rowTotal,
                'purchased_by'    => $request->purchased_by,
                'purchased_at'    => $request->purchased_at,
            ]);

            // -------- UPDATE STOCK --------
            $stock = StockItem::where('item_type', 'mess')->where('name', $item['ingredient_name'])->first();
            if ($stock) {
                $stock->total_quantity += $item['quantity'];
                $stock->available_qty += $item['quantity'];
                $stock->price = $item['price_per_unit'];
                $stock->total_cost += $rowTotal;
                $stock->save();
            } else {
                StockItem::create([
                    'item_type'        => 'mess',
                    'name'             => $item['ingredient_name'],
                    'product_id'       => null,
                    'asset_id'         => null,
                    'total_quantity'   => $item['quantity'],
                    'available_qty'    => $item['quantity'],
                    'damaged_quantity' => 0,
                    'price'            => $item['price_per_unit'],
                    'unit'             => $item['unit'],
                    'total_cost'       => $rowTotal,
                ]);
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'All items stored successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'purchases' => 'required|array|min:1',
    //         'purchases.*.ingredient_name' => 'required|string',
    //         'purchases.*.quantity' => 'required|numeric',
    //         'purchases.*.unit' => 'required|string',
    //         'purchases.*.price_per_unit' => 'required|numeric',
    //         'purchased_by' => 'required|string',
    //         'purchased_at' => 'required|date',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         foreach ($request->purchases as $item) {
    //             $total_cost = $item['quantity'] * $item['price_per_unit'];

    //             $purchase = new MessItemsPurchase();
    //             $purchase->ingredient_name = $item['ingredient_name'];
    //             $purchase->quantity = $item['quantity'];
    //             $purchase->unit = $item['unit'];
    //             $purchase->price_per_unit = $item['price_per_unit'];
    //             $purchase->total_cost = $total_cost;
    //             $purchase->purchased_by = $request->purchased_by;
    //             $purchase->purchased_at = $request->purchased_at;
    //             $purchase->save();

    //             $stock = StockItem::where('item_type', 'mess')->where('name', $item['ingredient_name'])->first();
    //             if ($stock) {
    //                 $stock->total_quantity += $item['quantity'];
    //                 $stock->available_qty += $item['quantity'];
    //                 $stock->price = $item['price_per_unit'];
    //                 $stock->total_cost += $item['price_per_unit'] * $item['quantity'];
    //                 $stock->save();
    //             } else {
    //                 $stock = new StockItem();
    //                 $stock->item_type = 'mess';
    //                 $stock->name = $item['ingredient_name'];
    //                 $stock->product_id = null;
    //                 $stock->asset_id = null;
    //                 $stock->total_quantity = $item['quantity'];
    //                 $stock->available_qty = $item['quantity'];
    //                 $stock->damaged_quantity = 0;
    //                 $stock->price = $item['price_per_unit'];
    //                 $stock->unit = $item['unit'];
    //                 $stock->total_cost = $item['price_per_unit'] * $item['quantity'];
    //                 $stock->save();
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->back()->with('success', 'Items purchased and stock updated successfully.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    //     }
    // }

    public function show(MessItemsPurchase $messItemsPurchase)
    {
        //
    }

    public function edit(MessItemsPurchase $messItemsPurchase)
    {
        //
    }

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

    public function invoices()
{
    $invoices = DB::table('mess_items_purchases')
        ->select('invoice_no','purchased_by','purchased_at', DB::raw('SUM(total_cost) as grand_total'))
        ->groupBy('invoice_no', 'purchased_by', 'purchased_at')
        ->orderBy('purchased_at', 'desc')
        ->get();

    return view('mess_items_purchases.invoices', compact('invoices'));
}

public function showInvoice($invoice_no)
{
    $items = DB::table('mess_items_purchases')->where('invoice_no', $invoice_no)->get();
    if ($items->isEmpty()) {
        return redirect()->back()->with('error','Invoice not found');
    }
    $invoice = $items->first();
    $grandTotal = $items->sum('total_cost');
    return view('mess_items_purchases.show_invoice', compact('items','invoice','grandTotal'));
}
}


