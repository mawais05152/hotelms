<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockItem;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\RestaurantAsset;
use App\Models\AssetPriceHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Purchase::latest()->get();
        return view('purchases.index', compact('assets'));
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
            'item_type' => 'required|in:product,asset',
            'item_id' => 'required|string',
            'variation_id' => 'nullable|required_if:item_type,product|integer',
            'total_quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $itemType = $request->item_type;
    $itemId = $request->item_id;
    $variationId = $itemType === 'product' ? $request->variation_id : null;

    $name = null;
    if ($itemType === 'product') {
        $product = Product::find($itemId);
        $name = $product ? $product->name : null;
    } else {
        $asset = RestaurantAsset::find($itemId);
        $name = $asset ? $asset->name : null;
    }

    $existingPurchase = Purchase::where('asset_type', $itemType)
        ->where('name', $name)
        ->when($itemType === 'product', function ($query) use ($variationId) {
            return $query->where('variation_id', $variationId);
        })
        ->first();

    if ($existingPurchase) {
        return redirect()->back()->withInput()->withErrors([
            'item_id' => 'This item has already been purchased. Please update the existing entry instead.',
        ]);
    }

    $purchase = Purchase::create([
        'invoice_no' => 'INV-' . now()->timestamp,
        'name' => $name,
        'asset_type' => $itemType,
        'variation_id' => $variationId,
        'total_quantity' => $request->total_quantity,
        'price' => $request->price ?? 0,
        'supplier_name' => $request->supplier_name,
        'purchase_date' => $request->purchase_date,
        'notes' => $request->notes,
    ]);

    $stock = StockItem::where('item_type', $itemType)
        ->where($itemType === 'product' ? 'product_id' : 'asset_id', $itemId)
        ->first();

    // if ($stock) {
    //     $stock->total_quantity += $request->total_quantity;
    //     $stock->available_qty += $request->total_quantity;
    //     $stock->save();
    // } else {
        StockItem::create([
            'item_type' => $itemType,
            'product_id' => $itemType === 'product' ? $itemId : null,
            'asset_id' => $itemType === 'asset' ? $itemId : null,
            'total_quantity' => $request->total_quantity,
            'price' => $request->price ?? 0,
            'damaged_quantity' => 0,
            'available_qty' => $request->total_quantity,
        ]);
    // }

    return redirect()->route('purchases.index')->with('success', 'Purchase entry added successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase $purchase, $id)
    {
        $restaurantAsset = Purchase::findorfail($id);
        return view('purchases.index', compact('restaurantAsset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $restaurantAsset)
    {
        $request->validate([
            'name' => 'required|unique:purchases,name,' . $restaurantAsset->id,
            'asset_type' => 'required',
            'total_quantity' => 'required|integer',
            'price' => 'nullable|numeric',
            'supplier_name' => 'required',
            'warehouse_name' => 'required',
            'purchase_date' => 'required|date',
        ]);

        $restaurantAsset->name = $request->name;
        $restaurantAsset->asset_type = $request->asset_type;
        $restaurantAsset->total_quantity = $request->total_quantity;
        $restaurantAsset->price = $request->price ?? 0;
        $restaurantAsset->supplier_name = $request->supplier_name;
        $restaurantAsset->warehouse_name = $request->warehouse_name;
        $restaurantAsset->purchase_date = $request->purchase_date;
        $restaurantAsset->notes = $request->notes;
        $restaurantAsset->save();

        return redirect()->route('purchase.index')->with('success', 'Item updated successfully.');
    }

    //    public function destroy(Purchase $purchase)
    //     {
    //         $purchase->delete();
    //         return back()->with('success', 'Item deleted successfully.');
    //     }
    public function destroy(Purchase $purchase)
    {
        $itemType = $purchase->asset_type;
        $itemId = $purchase->name;

        $stock = StockItem::where('item_type', $itemType)
            ->where($itemType === 'product' ? 'product_id' : 'asset_id', $itemId)
            ->first();

        if ($stock) {
            $stock->delete();
        }

        $purchase->delete();

        return back()->with('success', 'Purchase and related stock item deleted successfully.');
    }


    public function getItems($type)
    {
        if ($type === 'product') {
            $items = Product::with('variation')->select('id', 'name', 'price')->get();
        } elseif ($type === 'asset') {
            $items = RestaurantAsset::select('id', 'name')->get();
        } else {
            return response()->json([], 404);
        }

        $items = $items->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'unit' => $item->unit ?? ($item->variation->unit ?? ''),
            'size' => $item->size ?? ($item->variation->size ?? ''),
            'price' => $item->price ?? '',
        ]);

        return response()->json($items);
    }

    public function getVariations($productId)
    {
        $variations = Variation::where('product_id', $productId)
            ->select('id', 'unit', 'size')
            ->get();

        return response()->json($variations);
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'new_price' => 'required|numeric',
            'additional_qty' => 'required|integer',
            'supplier_name' => 'required|string',
            'warehouse_name' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $asset = RestaurantAsset::findOrFail($id);
            $oldPrice = $asset->price;

            $asset->price = $request->new_price;
            $asset->total_quantity += $request->additional_qty;
            $asset->supplier_name = $request->supplier_name;
            $asset->warehouse_name = $request->warehouse_name;
            $asset->save();

            AssetPriceHistory::create([
                // dd($request->all()),
                'asset_id' => $asset->id,
                'old_price' => $oldPrice,
                'new_price' => $request->new_price,
                'added_quantity' => $request->additional_qty,
                'supplier_name' => $request->supplier_name,
                'warehouse_name' => $request->warehouse_name,
                'note' => $request->note,
            ]);

            $stockItem = StockItem::where('asset_id', $asset->id)->first();

            if ($stockItem) {
                $stockItem->total_quantity += $request->additional_qty;
                $stockItem->price = $request->new_price;
                $stockItem->available_qty += $request->additional_qty;
                $stockItem->supplier_name = $request->supplier_name ?? $stockItem->supplier_name;
                $stockItem->save();
            } else {
                StockItem::create([
                    'name' => $asset->name,
                    'item_type' => 'asset',
                    'asset_id' => $asset->id,
                    'product_id' => null,
                    'price' => $request->new_price,
                    'total_quantity' => $request->additional_qty,
                    'available_qty' => $request->additional_qty,
                    'damaged_quantity' => 0,
                ]);
            }

            Purchase::create([
                'invoice_no' => 'INV-' . time(),
                'name' => $asset->name,
                'asset_type' => 'asset',
                'variation_id' => null,
                'total_quantity' => $request->additional_qty,
                'price' => $request->new_price,
                'supplier_name' => $request->supplier_name,
                'purchase_date' => now()->toDateString(),
                'notes' => $request->note,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Asset updated and purchase recorded.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Asset Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }


    }

    public function getPriceHistory($id){
        $history = AssetPriceHistory::where('asset_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($history);
    }
}
