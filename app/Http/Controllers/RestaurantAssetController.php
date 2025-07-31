<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockItem;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\InventoryStock;
use App\Models\RestaurantAsset;
use App\Models\AssetPriceHistory;

class RestaurantAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $assets = RestaurantAsset::all();
        return view('restaurant_assets.index', compact('assets'));
    }

    public function create()
    {
        return view('restaurant_assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:restaurant_assets',
            'category' => 'required',
        ]);

        RestaurantAsset::create($request->all());

        return redirect()->route('restaurant-assets.index')->with('success', 'Asset added successfully.');
    }

    public function edit($id)
    {
        $asset = RestaurantAsset::findOrFail($id);
        return view('restaurant_assets.edit', compact('asset'));
    }

    public function update(Request $request, $id)
    {
        $asset = RestaurantAsset::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:restaurant_assets,name,' . $id,
            'category' => 'required',
        ]);

        $asset->update($request->all());

        return redirect()->route('restaurant-assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy($id)
    {
        RestaurantAsset::findOrFail($id)->delete();
        return redirect()->route('restaurant-assets.index')->with('success', 'Asset deleted successfully.');
    }


}
