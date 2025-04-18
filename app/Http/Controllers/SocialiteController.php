<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // Redirect to social provider
    public function redirectToProvider($provider)
    {
        if ($provider) {
            return Socialite::driver($provider)->redirect();
        }
        abort(404);
    }

    // Handle provider callback
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'avatar' => $socialUser->getAvatar(),
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($user);

            return redirect()->route('products.index');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login failed');
        }
    }
}
