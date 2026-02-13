<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
     return view('users.index');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Password::min(8)->letters()->numbers()->symbols()],
            'role' => ['required', 'string', 'in:Admin,Waiter'],
        ]);
        if ($request->role === 'Admin') {
            if (!Auth::check() || Auth::user()->role !== 'Admin') {
                return redirect()->back()->withErrors(['role' => 'You do not have permission to register an Admin user.']);
            }
        }
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        return redirect('/users')->with('success', 'User added successfully');
    }

    public function show(string $id)
    {
        //
    }

   public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('users.index', compact('users'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
            'role' => ['required', 'string', 'in:Admin,Waiter'],
        ]);
            if ($request->role === 'Admin') {
                if (!Auth::check() || Auth::user()->role !== 'Admin') {
                    return redirect()->back()->withErrors(['role' => 'You do not have permission to register an Admin user.']);
                }
            }
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect()->back()->with('success','User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'Admin' && Auth::user()->role !== 'Admin') {
            return redirect()->back()->withErrors(['role' => 'You do not have permission to delete an Admin user.']);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
