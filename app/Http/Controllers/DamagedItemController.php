<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StockItem;
use App\Models\Variation;
use App\Models\DamagedItem;
use Illuminate\Http\Request;

class DamagedItemController extends Controller
{
    public function index(Request $request)
    {
        $damagedItems = DamagedItem::with(['stockItem', 'variation', 'user'])->get();
        $users = User::all();

    return view('damaged_items.index', compact('damagedItems', 'users'));

    }
//
    // public function store(Request $request)
    // {
        // $request->validate([
        //     'item_type' => 'required|in:product,asset',
        //     'quantity' => 'required|integer|min:1',
        //     'damage_date' => 'required|date',
        //     'fine_amount' => 'nullable|numeric',
        //     'reason' => 'nullable|string',
        //     'variation_id' => 'required|exists:variations,id',
        // ]);

        // $damaged = new DamagedItem();
        // $damaged->item_type = $request->item_type;
        // $damaged->stock_item_id = $request->stock_item_id;
        // $damaged->user_id = auth()->id();
        // $damaged->quantity = $request->quantity;
        // $damaged->damage_date = $request->damage_date;
        // $damaged->fine_amount = $request->fine_amount ?? 0;
        // $damaged->reason = $request->reason;
        // $damaged->variation_id = $request->variation_id;
        // $damaged->save();


    //     return redirect()->route('damaged_items.index')->with('success', 'Damaged item added successfully.');
    // }

    // correct
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'item_type' => 'required|in:product,asset',
    //         'variation_id' => 'required',
    //         // 'stock_item_id' => 'required|exists:stock_items,id',
    //         'item_id' => 'required',
    //         'quantity' => 'required|integer|min:1',
    //         'damage_date' => 'required|date',
    //         'fine_amount' => 'nullable|numeric',
    //         'reason' => 'nullable|string',
    //     ]);

    //     $stockItem = StockItem::where('id', $request->stock_item_id)
    //         ->where('variation_id', $request->variation_id)
    //         ->first();

    //     if (!$stockItem) {
    //         return back()->withErrors([
    //             'stock_item_id' => 'Stock item with selected variation not found.'
    //         ]);
    //     }

    //     if ($stockItem->available_qty < $request->quantity) {
    //         return back()->withErrors([
    //             'quantity' => 'Not enough available quantity to mark as damaged.'
    //         ]);
    //     }

    //     $damaged = new DamagedItem();
    //     dd($request->all());
    //     $damaged->item_type = $request->item_type;
    //     // $damaged->stock_item_id = $stockItem->id;
    //     $damaged->stock_item_id = $request->item_id;
    //     $damaged->variation_id = $request->variation_id;
    //     $damaged->user_id = auth()->id();
    //     $damaged->quantity = $request->quantity;
    //     $damaged->damage_date = $request->damage_date;
    //     $damaged->fine_amount = $request->fine_amount ?? 0;
    //     $damaged->reason = $request->reason;
    //     $damaged->save();

    //     //  Update stock
    //     $stockItem->damaged_quantity += $request->quantity;
    //     $stockItem->available_qty -= $request->quantity;

    //     if ($stockItem->available_qty == 0) {
    //         $stockItem->delete();
    //     } else {
    //         $stockItem->save();
    //     }

    //     return redirect()->route('damaged_items.index')->with('success', 'Damaged item added successfully.');
    // }
    public function store(Request $request)
{
    $request->validate([
        'item_type' => 'required|in:product,asset',
        'unit' => 'required',
        'size' => 'required',
        'item_id' => 'required|exists:stock_items,id',
        'quantity' => 'required|integer|min:1',
        'damage_date' => 'required|date',
        'fine_amount' => 'nullable|numeric',
        'reason' => 'nullable|string',
    ]);

    $stockItem = StockItem::where('id', $request->item_id)
    ->where('unit', $request->unit)
    ->where('size', $request->size)
    ->first();

    if (!$stockItem) {
        return back()->withErrors([
            'item_id' => 'Stock item with selected unit and size not found.'
        ]);
    }

    if ($stockItem->available_qty < $request->quantity) {
        return back()->withErrors([
            'quantity' => 'Not enough available quantity to mark as damaged.'
        ]);
    }

    $damaged = new DamagedItem();
    $damaged->item_type = $request->item_type;
    $damaged->stock_item_id = $stockItem->id;
    $damaged->unit = $request->unit;
    $damaged->size = $request->size;
    $damaged->user_id = auth()->id();
    $damaged->quantity = $request->quantity;
    $damaged->damage_date = $request->damage_date;
    $damaged->fine_amount = $request->fine_amount ?? 0;
    $damaged->reason = $request->reason;
    $damaged->save();

    // Update stock
    $stockItem->damaged_quantity += $request->quantity;
    $stockItem->available_qty -= $request->quantity;

    if ($stockItem->available_qty == 0) {
        $stockItem->delete();
    } else {
        $stockItem->save();
    }

    return redirect()->route('damaged_items.index')->with('success', 'Damaged item added successfully.');
}



    public function update(Request $request, $id)
    {
        $request->validate([
            'item_type' => 'required|in:product,asset',
            'item_id' => 'required|exists:stock_items,id',
            'stock_item_id' => 'nullable|exists:stock_item,id',
            'quantity' => 'required|integer|min:1',
            'damage_date' => 'required|date',
            'fine_amount' => 'nullable|numeric',
            'reason' => 'nullable|string',
        ]);

        $damaged = DamagedItem::findOrFail($id);
        $damaged->item_type = $request->item_type;
        $damaged->item_id = $request->item_id;
        $damaged->stock_item_id = $request->stock_item_id;
        $damaged->user_id = auth()->id(); // damaged_by
        $damaged->quantity = $request->quantity;
        $damaged->damage_date = $request->damage_date;
        $damaged->fine_amount = $request->fine_amount ?? 0;
        $damaged->reason = $request->reason;
        $damaged->save();

        return redirect()->route('damaged-items.index')->with('success', 'Damaged item updated successfully.');
    }


    public function destroy($id)
    {
        $item = DamagedItem::findOrFail($id);
        $item->delete();

        return redirect()->route('damaged-items.index')->with('success', 'Damaged item deleted successfully.');
    }


}
