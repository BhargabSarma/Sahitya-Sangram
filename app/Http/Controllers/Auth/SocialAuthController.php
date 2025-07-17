<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $socialUser = Socialite::driver('google')->user();

        return $this->findOrCreateUser($socialUser, 'google');
    }

    // Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $socialUser = Socialite::driver('facebook')->user();

        return $this->findOrCreateUser($socialUser, 'facebook');
    }

    // Common user logic
    protected function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('provider_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)), // random password
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
