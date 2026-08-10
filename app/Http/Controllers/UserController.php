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
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function create() {
      return view('users.index');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Password::min(8)->letters()->numbers()->symbols()],
            'role' => ['required', 'string'],
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role // Keeping old column for compatibility
        ]);

        $user->assignRole($request->role);

        return redirect('/users')->with('success', 'User added successfully');
    }

    public function show(string $id)
    {
        //
    }

   public function edit($id)
    {
        $users = User::findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$id],
            'password' => ['nullable'],
            'role' => ['required', 'string'],
        ]);

        $user = User::findOrFail($id);
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        $user->syncRoles([$request->role]);

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
