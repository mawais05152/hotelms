<?php

namespace App\Http\Controllers;

use App\Models\MessMenu;
use Illuminate\Http\Request;
use App\Models\MessDistribution;

class MessDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distributions = MessDistribution::with('messMenu')->latest()->get();
        $menus = MessMenu::all();
        return view('mess_distributions.index', compact('distributions', 'menus'));
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
            'mess_meal_id' => 'required|exists:mess_menus,id',
            'person_name' => 'required|string',
            'quantity_given' => 'required|string',
            'remarks' => 'nullable|string',
            'delivered_at' => 'required|date',
        ]);

        $distribution = new MessDistribution();
        $distribution->mess_meal_id = $request->mess_meal_id;
        $distribution->person_name = $request->person_name;
        $distribution->quantity_given = $request->quantity_given;
        $distribution->remarks = $request->remarks;
        $distribution->delivered_at = $request->delivered_at;
        $distribution->save();

        return redirect()->back()->with('success', 'Distribution added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MessDistribution $messDistribution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessDistribution $messDistribution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $distribution = MessDistribution::findOrFail($id);

        $request->validate([
            'mess_meal_id' => 'required|exists:mess_menus,id',
            'person_name' => 'required|string',
            'quantity_given' => 'required|string',
            'remarks' => 'nullable|string',
            'delivered_at' => 'required|date',
        ]);

        $distribution->mess_meal_id = $request->mess_meal_id;
        $distribution->person_name = $request->person_name;
        $distribution->quantity_given = $request->quantity_given;
        $distribution->remarks = $request->remarks;
        $distribution->delivered_at = $request->delivered_at;
        $distribution->save();

        return redirect()->back()->with('success', 'Distribution updated successfully.');
    }

    public function destroy($id)
    {
        MessDistribution::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Distribution deleted successfully.');
    }
}
