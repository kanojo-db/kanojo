<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountSettingsRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
                'show_jav' => true,
                'show_vr' => true,
                'show_gravure' => true,
                'show_minors' => true,
            ],
        ]);
    }

    public function update(UpdateAccountSettingsRequest $request): RedirectResponse
    {
        if (! Auth::check()) {
            abort(401, 'Unauthorized');
        }

        /** @var User|null */
        $user = Auth::user();

        if ($user === null) {
            abort(401, 'Unauthorized');
        }

        $validatedData = $request->validated();

        /*
        $user->settings->set('show_jav', $validatedData->show_jav);
        $user->settings->set('show_vr', $validatedData->show_vr);
        $user->settings->set('show_gravure', $validatedData->show_gravure);
        $user->settings->set('show_minors', $validatedData->show_minors);
         */

        return back();
    }
}
