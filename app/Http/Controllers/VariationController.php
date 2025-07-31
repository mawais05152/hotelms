<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockItem;
use App\Models\Variation;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function index()
    {
        $variations = Variation::with('product')->get();
        $products = Product::all();
        return view('variations.index', compact('variations', 'products'));

    }

    public function create()
    {
        return view('variations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required',
            'size' => 'required',
            'price' => 'required',
        ]);

        $variation = new Variation();
        // dd($request);
        $variation->product_id = $request->product_id;
        $variation->unit = $request->unit;
        $variation->size = $request->size;
        $variation->price = $request->price;
        $variation->save();

        return redirect()->route('variations.index')->with('success', 'Variation added successfully.');
    }

    public function edit(Variation $variation)
    {
        return view('variations.edit', compact('variation'));
    }

    public function update(Request $request, Variation $variation)
    {
        $request->validate([
            'unit' => 'required',
            'size' => 'required',
            'price' => 'required',
        ]);
        $variation->product_id = $request->product_id;
        $variation->unit = $request->unit;
        $variation->size = $request->size;
        $variation->price = $request->price;
        $variation->save();

        return redirect()->route('variations.index')->with('success', 'Variation updated successfully.');
    }

    public function destroy(Variation $variation)
    {
        $variation->delete();
        return redirect()->route('variations.index')->with('success', 'Variation deleted successfully.');
    }
}
