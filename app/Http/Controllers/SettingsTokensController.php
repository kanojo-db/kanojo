<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class SettingsTokensController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var \App\Models\User|null */
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        return Inertia::render('Settings/Tokens', [
            'tokens' => $user->tokens,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Validator::make([
            'name' => $request->name,
        ], [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createApiToken');

        /** @var \App\Models\User|null */
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        /** @var string */
        $tokenName = $request->name;

        $token = $user->createToken(
            $tokenName, ['read']
        );

        return back()->with('_flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ])->with('status', 'api-token-created');
    }

    public function destroy(Request $request, int $tokenId): RedirectResponse
    {
        /** @var \App\Models\User|null */
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        $user->tokens()->where('id', $tokenId)->delete();

        return back();
    }
}
