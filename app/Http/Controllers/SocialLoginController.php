<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Log;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialLoginController extends Controller
{
    public function redirect(Request $request, string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        // Validate that provider is allowed.
        if (! in_array($provider, ['github', 'google'])) {
            return abort(404);
        }

        try {
            /** @var \Laravel\Socialite\Contracts\User */
            $user = Socialite::driver($provider)->user();

            // Build the column name to use for the provider.
            $column = $provider.'_id';

            $findUser = User::where($column, $user->getId())->first();

            if ($findUser) {
                Auth::login($findUser);

                return redirect()->intended('/');
            }

            $newUser = User::create([
                'name' => $user->getName(),
                $column => $user->getId(),
                'email' => $user->getEmail(),
                'profile_photo_path' => $user->getAvatar(),
            ]);

            Auth::login($newUser);

            return redirect()->route('welcome');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return abort(500);
        }
    }
}
