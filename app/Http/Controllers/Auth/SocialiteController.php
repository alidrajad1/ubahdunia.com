<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;  
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class SocialiteController extends Controller
{
    /**
     * Redirect the user to the OAuth provider.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the OAuth provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();

            $user = User::where('provider_id', $socialiteUser->getId())
                        ->where('provider_name', $provider)
                        ->first();

            if (!$user) {

                $user = User::create([
                    'name' => $socialiteUser->getName(),
                    'email' => $socialiteUser->getEmail(),
                    'provider_id' => $socialiteUser->getId(),
                    'provider_name' => $provider,
                    'password' => Hash::make(Str::random(24))
                ]);
            }

            Auth::login($user);

            return redirect('/dashboard');

        } catch (Exception $e) {
            Log::error("Socialite callback error for provider {$provider}: " . $e->getMessage());
            return redirect('/login')->withErrors(['oauth' => 'Gagal login dengan ' . ucfirst($provider) . '. Silakan coba lagi.']);
        }
    }
}
