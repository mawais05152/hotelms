<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'role' => ['required', 'string', 'in:Admin,User'], // Ensure role is either Admin or User
        ]);
            // if ($request->role === 'Admin') {
            //     if (!Auth::check() || Auth::user()->role !== 'Admin') {
            //         return redirect()->back()->withErrors(['role' => 'You do not have permission to register an Admin user.']);
            //     }
            // }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Waiter',
        ]);

        event(new Registered($user));
         if (Auth::check()) {
        return redirect('/users')->with('success', 'User added successfully.');
    }

        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);

    }
}
