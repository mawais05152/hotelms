<?php

namespace App\Http\Controllers;

use App\Models\AssetPriceHistory;
use Illuminate\Http\Request;

class AssetPriceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($assetId)
    {
        $histories = AssetPriceHistory::where('asset_id', $assetId)->orderBy('created_at', 'desc')->get();
        return view('asset_price_histories.index', compact('histories'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetPriceHistory $assetPriceHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetPriceHistory $assetPriceHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetPriceHistory $assetPriceHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetPriceHistory $assetPriceHistory)
    {
        //
    }
}
