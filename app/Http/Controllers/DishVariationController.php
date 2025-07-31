<?php

namespace App\Http\Controllers;

use App\Models\MessMenu;
use Illuminate\Http\Request;
use App\Models\DishVariation;

class DishVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dishVariations = DishVariation::with('dish')->get();
        return view('dish_variations.index', compact('dishVariations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dishes = MessMenu::all();
        return view('dish_variations.create', compact('dishes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mess_menu_id' => 'nullable|exists:mess_menus,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $variation = new DishVariation();
        $variation->mess_menu_id = $request->mess_menu_id;
        $variation->name = $request->name;
        $variation->price = $request->price;
        $variation->save();

        return redirect()->route('dish_variations.index')->with('success', 'Dish variation created.');
    }


    /**
     * Display the specified resource.
     */
    public function show(DishVariation $dishVariation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DishVariation $dishVariation)
    {
        $dishes = MessMenu::all();
        return view('dish_variations.edit', compact('dishVariation', 'dishes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DishVariation $dishVariation)
    {
        $request->validate([
            'mess_menu_id' => 'nullable|exists:mess_menus,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $dishVariation->mess_menu_id = $request->mess_menu_id;
        $dishVariation->name = $request->name;
        $dishVariation->price = $request->price;
        $dishVariation->save();

        return redirect()->route('dish_variations.index')->with('success', 'Dish variation updated.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DishVariation $dishVariation)
    {
        $dishVariation->delete();
        return redirect()->route('dish_variations.index')->with('success', 'Dish variation deleted.');
    }
}
