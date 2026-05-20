<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // First try to find by email (handles users who registered via email/password first)
            $user = User::where('email', $googleUser->getEmail())->first();

            $adminEmail = env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com');

            if ($user) {
                $user->update([
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                $isAdmin = strtolower($googleUser->getEmail()) === strtolower($adminEmail);
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'is_approved'       => $isAdmin,
                ]);
            }

            Auth::login($user, true);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if (!$user->isApproved()) {
                return redirect()->route('approval.pending');
            }

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again or use email/password.');
        }
    }
}
