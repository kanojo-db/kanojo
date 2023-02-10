<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SettingsAccountController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Settings/Account', [
            'settings' => [
                'show_jav' => Auth::user()->settings->get('show_jav'),
                'show_vr' => Auth::user()->settings->get('show_vr'),
                'show_gravure' => Auth::user()->settings->get('show_gravure'),
                'show_minors' => Auth::user()->settings->get('show_minors'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'show_jav' => 'required|boolean',
            'show_vr' => 'required|boolean',
            'show_gravure' => 'required|boolean',
            'show_minors' => 'required|boolean',
        ]);

        Auth::user()->settings->set('show_jav', $request->show_jav);
        Auth::user()->settings->set('show_vr', $request->show_vr);
        Auth::user()->settings->set('show_gravure', $request->show_gravure);
        Auth::user()->settings->set('show_minors', $request->show_minors);

        return back();
    }
}
