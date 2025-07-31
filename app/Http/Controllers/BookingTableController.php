<?php

namespace App\Http\Controllers;

use App\Models\BookingTable;
use Illuminate\Http\Request;

class BookingTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function create() {
        return view('booking_tables.index');
    }

    public function index() {
        $bookingTables = BookingTable::all();
        return view('booking_tables.index', compact('bookingTables'));
    }

    public function store(Request $request) {
        $request->validate(['table_number' => 'required', 'status' => 'required']);
        BookingTable::create($request->all());
        return redirect()->route('bookingtables.index')->with('success', 'Table booked successfully!');
    }

    public function update(Request $request, $id) {
        $table = BookingTable::findOrFail($id);
        $table->update($request->all());
        return redirect()->route('bookingtables.index', ['success' => 'Table updated successfully!']);
    }

    public function destroy($id) {
        BookingTable::findOrFail($id)->delete();
        return redirect()->route('bookingtables.index', ['success' => 'Table deleted successfully!']);
    }
}
