<?php

namespace App\Http\Controllers;

use App\Models\MessFinance;
use Illuminate\Http\Request;

class MessFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $finances = MessFinance::with('messMenu')->latest()->get();
        return view('mess_finances.index', compact('finances'));
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
            'total_cost' => 'required|numeric',
            'price_per_person' => 'required|numeric',
            'persons_served' => 'required|integer',
            'total_income' => 'required|numeric',
            'profit_or_loss' => 'required|string',
        ]);

        $finance = new MessFinance();
        $finance->mess_meal_id = $request->mess_meal_id;
        $finance->total_cost = $request->total_cost;
        $finance->price_per_person = $request->price_per_person;
        $finance->persons_served = $request->persons_served;
        $finance->total_income = $request->total_income;
        $finance->profit_or_loss = $request->profit_or_loss;
        $finance->save();

        return redirect()->back()->with('success', 'Finance record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MessFinance $messFinance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessFinance $messFinance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $finance = MessFinance::findOrFail($id);

        $request->validate([
            'mess_meal_id' => 'required|exists:mess_menus,id',
            'total_cost' => 'required|numeric',
            'price_per_person' => 'required|numeric',
            'persons_served' => 'required|integer',
            'total_income' => 'required|numeric',
            'profit_or_loss' => 'required|string',
        ]);

        $finance = MessFinance::findOrFail($id);
        $finance->mess_meal_id = $request->mess_meal_id;
        $finance->total_cost = $request->total_cost;
        $finance->price_per_person = $request->price_per_person;
        $finance->persons_served = $request->persons_served;
        $finance->total_income = $request->total_income;
        $finance->profit_or_loss = $request->profit_or_loss;
        $finance->save();

        return redirect()->back()->with('success', 'Finance record updated successfully.');
    }

    public function destroy($id)
    {
        MessFinance::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Finance record deleted.');
    }
}
