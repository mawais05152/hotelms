<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\MessMenu;
use App\Models\StockItem;
use App\Models\MenuMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $menus = MessMenu::all();
        $materials = MenuMaterial::all();
        $uniqueUnits = $materials->pluck('unit')->unique();
        return view('mess_menus.index', compact('menus', 'materials','uniqueUnits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = MenuMaterial::all();
        $units = MenuMaterial::select('unit')->distinct()->pluck('unit');

        return view('mess_menus.create', compact('materials', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */

    // correct
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $messMenu = MessMenu::create([
    //             // dd($request->all()),
    //             'meal_name' => $request->meal_name,
    //             'date' => $request->date,
    //             'cooked_by' => $request->cooked_by,
    //             'cooked_for_persons' => $request->cooked_for_persons,
    //             'quantity_made' => $request->quantity_made,
    //             'ingredient_name' => $request->ingredient_name,
    //             'quantity_used' => $request->quantity_used,
    //             'unit' => $request->unit,
    //         ]);

    //         $stockItem = StockItem::where('name', $request->ingredient_name)->first();

    //         if ($stockItem) {
    //             $stockItem->total_quantity -= $request->quantity_used;
    //             $stockItem->available_qty -= $request->quantity_used;
    //             $stockItem->save();
    //         }

    //         DB::commit();

    //         return back()->with('success', 'Mess menu added and stock updated!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    //     }
    // }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $messMenu = MessMenu::create([
                    // dd($request->all()),
                    'meal_name' => $request->meal_name,
                    'date' => $request->date,
                    'cooked_by' => $request->cooked_by,
                    'cooked_for_persons' => $request->cooked_for_persons,
                    'quantity_made' => $request->quantity_made,
                    'ingredient_name' => $request->ingredient_name,
                    'quantity_used' => $request->quantity_used,
                    'unit' => $request->unit,
                ]);

            $stockItem = StockItem::where('name', $request->ingredient_name)->first();

            if ($stockItem) {
                $perUnitPrice = $stockItem->total_quantity > 0
                    ? $stockItem->price / $stockItem->total_quantity
                    : 0;
                $priceToDeduct = $perUnitPrice * $request->quantity_used;
                $stockItem->total_quantity -= $request->quantity_used;
                $stockItem->available_qty -= $request->quantity_used;
                $stockItem->total_cost -= $priceToDeduct;
                $stockItem->total_quantity = max(0, $stockItem->total_quantity);
                $stockItem->available_qty = max(0, $stockItem->available_qty);
                $stockItem->price = max(0, $stockItem->price);
                $stockItem->save();
            } else {
                DB::rollBack();
                return back()->withErrors(['error' => 'Stock item not found for: ' . $request->ingredient_name]);
            }

            DB::commit();

            return back()->with('success', 'Mess menu added and stock updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MessMenu $messMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessMenu $messMenu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $menu = MessMenu::findOrFail($id);

        $request->validate([
            'meal_name' => 'required',
            'date' => 'required|date',
            'cooked_by' => 'required',
            'cooked_for_persons' => 'required|integer',
            'quantity_made' => 'required',
        ]);

        $menu->meal_name = $request->meal_name;
        $menu->date = $request->date;
        $menu->cooked_by = $request->cooked_by;
        $menu->cooked_for_persons = $request->cooked_for_persons;
        $menu->quantity_made = $request->quantity_made;
        $menu->save();

        return redirect()->back()->with('success', 'Meal updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MessMenu::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Meal deleted successfully.');
    }
}
