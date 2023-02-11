<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SettingsAccountController extends Controller
{
    public function show(): Response
    {
        if (! Auth::check()) {
            abort(401, 'Unauthorized');
        }
        
        /** @var User|null */
        $user = Auth::user();
        
        if ($user === null) {
            abort(401, 'Unauthorized');
        }

        return Inertia::render('Settings/Account', [
            'settings' => [
                'show_jav' => $user->settings->get('show_jav'),
                'show_vr' => $user->settings->get('show_vr'),
                'show_gravure' => $user->settings->get('show_gravure'),
                'show_minors' => $user->settings->get('show_minors'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            abort(401, 'Unauthorized');
        }

        $request->validate([
            'show_jav' => ['required', 'boolean'],
            'show_vr' => ['required', 'boolean'],
            'show_gravure' => ['required', 'boolean'],
            'show_minors' => ['required', 'boolean'],
        ]);

        /** @var User|null */
        $user = Auth::user();

        if ($user === null) {
            abort(401, 'Unauthorized');
        }

        $validatedData = $request->validated();

        $user->settings->set('show_jav', $validatedData->show_jav);
        $user->settings->set('show_vr', $validatedData->show_vr);
        $user->settings->set('show_gravure', $validatedData->show_gravure);
        $user->settings->set('show_minors', $validatedData->show_minors);

        return back();
    }
}
