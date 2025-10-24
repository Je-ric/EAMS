<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // admin lang toh
    // Used by:
    //      - resources/views/partials/adminModal.blade.php (Admin login modal form)
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Admins only.']);
            }

            return redirect()->route('index');
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }

}
