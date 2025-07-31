<?php

namespace App\Http\Controllers;

use App\Models\MessMenu;
use App\Models\MenuMaterial;
use Illuminate\Http\Request;

class MenuMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = MenuMaterial::latest()->get();
        // $menus = MessMenu::latest()->get();
        return view('menu_materials.index', compact('materials'));
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
            // 'mess_meal_id' => 'required|exists:mess_menus,id',
            'ingredient_name' => 'required',
            'quantity_used' => 'required|numeric',
            'unit' => 'required',
        ]);

        $material = new MenuMaterial();
        // $material->mess_meal_id = $request->mess_meal_id;
        $material->ingredient_name = $request->ingredient_name;
        $material->quantity_used = $request->quantity_used;
        $material->unit = $request->unit;
        $material->save();

        return redirect()->back()->with('success', 'Material added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuMaterial $menuMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuMaterial $menuMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = MenuMaterial::findOrFail($id);

        $request->validate([
            // 'mess_meal_id' => 'required|exists:mess_menus,id',
            'ingredient_name' => 'required',
            'quantity_used' => 'required|numeric',
            'unit' => 'required',
        ]);

        // $material->mess_meal_id = $request->mess_meal_id;
        $material->ingredient_name = $request->ingredient_name;
        $material->quantity_used = $request->quantity_used;
        $material->unit = $request->unit;
        $material->save();

        return redirect()->back()->with('success', 'Material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MenuMaterial::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Material deleted successfully.');
    }
}
