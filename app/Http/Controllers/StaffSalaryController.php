<?php

namespace App\Http\Controllers;

use App\Models\StaffSalary;
use App\Models\User;
use Illuminate\Http\Request;

class StaffSalaryController extends Controller
{
    public function index() {
        $salaries = StaffSalary::with('user')->latest()->get();
        return view('staff_salaries.index', compact('salaries'));
    }

    public function create() {
        $users = User::all();
        return view('staff_salaries.create', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_amount' => 'required|numeric',
            'salary_month' => 'required|date_format:Y-m',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending,Cancelled',
        ]);

        $salary = new StaffSalary();
        $salary->user_id = $request->user_id;
        $salary->salary_amount = $request->salary_amount;
        $salary->salary_month = $request->salary_month;
        $salary->paid_date = $request->paid_date;
        $salary->status = $request->status;
        $salary->save();

        return redirect()->route('staff-salaries.index')->with('success', 'Salary record added successfully.');
    }

    public function edit($id) {
        $salary = StaffSalary::findOrFail($id);
        $users = User::all();
        return view('staff_salaries.edit', compact('salary', 'users'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_amount' => 'required|numeric',
            'salary_month' => 'required|date_format:Y-m',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:Paid,Pending,Cancelled',
        ]);

        $salary = StaffSalary::findOrFail($id);
        $salary->user_id = $request->user_id;
        $salary->salary_amount = $request->salary_amount;
        $salary->salary_month = $request->salary_month;
        $salary->paid_date = $request->paid_date;
        $salary->status = $request->status;
        $salary->save();

        return redirect()->route('staff-salaries.index')->with('success', 'Salary record updated successfully.');
    }

    public function destroy($id) {
        $salary = StaffSalary::findOrFail($id);
        $salary->delete();

        return redirect()->route('staff-salaries.index')->with('success', 'Salary record deleted successfully.');
    }
}
