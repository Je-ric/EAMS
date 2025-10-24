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
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

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

            $employee = Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => 'Employee',
                    'login_provider' => 'google',
                    'emp_pic' => $googleUser->getAvatar(),
                ]
            );


            return redirect()->route('index');
        } catch (\Exception $e) {
            return redirect()->route('index')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
    
    // ----------------------------------------------------------------------------------
    // alam niyo na yan, pangalan palang ng controller HAHAHAHAHA

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
