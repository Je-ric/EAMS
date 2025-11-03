<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    // Used by:
    //  - resources/views/home.blade.php (Register as Employee button -> Open Modal)
    //  - resources/views/partials/registerEmpModal.blade.php (may dalawang button, yun yung dalawang redirect)

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless() // for Google to avoid session state when needed.
            ->with(['prompt' => 'select_account']) // always ask account
            ->redirect();
    }

    // Flow:
    // 1 Retrieve user info from Google.
    // 2 Create or update a User by email.
    // 3 Create or update an Employee tied to that user.
    // 4 Redirect back to index on success, or return with error message on failure.
    public function handleGoogleCallback()
    {
        try {
            // 1
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 2
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
                    // 'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                    'password' => Hash::make('password123'),
                ]
            );

            // 3
            $employee = Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => 'Employee',
                    'login_provider' => 'google',
                    'emp_pic' => $googleUser->getAvatar(),
                ]
            );

            // 4 success
            return redirect()->route('index');
        } catch (\Exception $e) {
            // 4 failure
            return redirect()->route('index')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------------------------
    // alam niyo na yan, pangalan palang ng controller HAHAHAHAHA
    // same process as google auth

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $facebookUser->getEmail()],
                [
                    'name' => $facebookUser->getName(),
                    'email_verified_at' => now(),
                    'role' => 'employee',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                ]
            );

            $employee = Employee::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => 'Employee',
                    'login_provider' => 'facebook',
                    'emp_pic' => $facebookUser->getAvatar(),
                ]
            );

            return redirect()->route('index')->with('showSetPasswordModal', true);
        } catch (\Exception $e) {
            return redirect()->route('index')->with('error', 'Facebook login failed: ' . $e->getMessage());
        }
    }
}
