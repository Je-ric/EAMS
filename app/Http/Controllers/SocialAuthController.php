<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        // Add ->stateless() here too to avoid "state mismatch" issues
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // Handle callback from Google
    public function handleGoogleCallback()
    {
        try {
            // Always use stateless for local testing
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Create or update user record
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                    'email_verified_at' => now(),
                    'role' => 'employee',
                    // give a random hashed password so DB NOT NULL constraints won't fail
                    // hindi kase nullable sa users table, but hindi naman natin gagamitin toh
                    // if google provider, employee.password
                    // if manual, user.password
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                ]
            );

            $employee = Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => 'Employee',
                    'login_provider' => 'google',
                    'emp_pic' => $googleUser->getAvatar(),
                    'password' => null,
                ]
            );


            return redirect()->route('index');

        } catch (\Exception $e) {
            return redirect()->route('index')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
