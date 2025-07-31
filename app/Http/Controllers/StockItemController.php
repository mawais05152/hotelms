<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockItem;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\RestaurantAsset;

class StockItemController extends Controller
{
    public function index(Request $request)
    {
        $stockItems = StockItem::with(['product'])->latest()->get();
        $categories = Category::all();
        $products = Product::all();
        $variations = Variation::all();

        return view('stock_items.index', compact('stockItems', 'categories', 'variations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:product,asset',
            'item_id' => 'required',
            'price' => 'required|numeric',
            'total_quantity' => 'required|integer|min:0',
            'damaged_quantity' => 'required|integer|min:0',
            'available_qty' => 'required|integer|min:0',
        ]);

        $item = new StockItem();
        $item->item_type = $request->item_type;
        $item->total_quantity = $request->total_quantity;
        $item->damaged_quantity = $request->damaged_quantity;
        $item->available_qty = $request->available_qty;

        if ($request->item_type === 'product') {
            $item->product_id = $request->item_id;
            $item->asset_id = null;
        } else {
            $item->product_id = null;
            $item->asset_id = $request->item_id;
        }

        $item->save();

        return redirect()->route('stock-items.index')->with('success', 'Stock item added successfully.');
    }


    public function show(StockItem $stockItem)
    {
        //
    }


    public function edit(StockItem $stockItem)
    {
        //
    }



    public function update(Request $request, StockItem $stockItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'item_type' => 'required|in:product,asset',
            'category_id' => 'required|exists:categories,id',
            'variation_id' => 'required|exists:variations,id',
            'price' => 'required|numeric',
            'total_quantity' => 'required|integer|min:0',
            'damaged_quantity' => 'required|integer|min:0',
            'available_qty' => 'required|integer|min:0',
        ]);

        $stockItem->name = $request->name;
        $stockItem->item_type = $request->item_type;
        $stockItem->category_id = $request->category_id;
        $stockItem->variation_id = $request->variation_id;
        $stockItem->price = $request->price;
        $stockItem->total_quantity = $request->total_quantity;
        $stockItem->damaged_quantity = $request->damaged_quantity;
        $stockItem->available_qty = $request->available_qty;
        $stockItem->save();

        return redirect()->route('stock-items.index')->with('success', 'Stock item updated successfully.');
    }

    public function destroy(StockItem $stockItem)
    {
        $stockItem->delete();

        return redirect()->route('stock-items.index')->with('success', 'Stock item deleted successfully.');
    }



    public function getVariations($productId)
    {
        return Variation::where('product_id', $productId)->get();
    }
}
