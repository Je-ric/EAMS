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
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                ]
            );

            // Create employee profile if it doesn’t exist
            $employee = Employee::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => 'Employee',
                    'login_provider' => 'google',
                    'emp_pic' => $googleUser->getAvatar(),
                ]
            );

            // Login user
            // Auth::login($user);

            // Redirect to your main page with a flag to show password modal
            // return redirect()->route('index')->with('showSetPasswordModal', true);
            return redirect()->route('index');

        } catch (\Exception $e) {
            return redirect()->route('index')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
