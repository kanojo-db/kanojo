<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SettingsTokensController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Settings/Tokens', [
            'tokens' => $request->user()->tokens,
        ]);
    }

    public function store(Request $request)
    {
        Validator::make([
            'name' => $request->name,
        ], [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createApiToken');

        $token = $request->user()->createToken(
            $request->name, ['read']
        );

        return back()->with('_flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ])->with('status', 'api-token-created');
    }

    public function destroy(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();
        return back();
    }
}
