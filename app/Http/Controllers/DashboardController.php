<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\BookingTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        $users = User::count();
        $tables = BookingTable::count();
        $products = Product::count();
        $ordersToday = Order::whereDate('created_at', today())->count();

        return view('dashboard', compact('users', 'tables', 'products', 'ordersToday'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($request)
    {
        //  dd($request->all());
        $user = Auth::user();
        return view('dashboard', compact('user'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
