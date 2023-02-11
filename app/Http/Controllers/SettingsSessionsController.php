<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Jenssegers\Agent\Agent;

class SettingsSessionsController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Settings/Sessions', [
            'sessions' => $this->sessions($request)->all(),
        ]);
    }

    /**
     * Get all the session for the user making the request.
     *
     * @return Collection<array-key, mixed>&static
     */
    public function sessions(Request $request): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function (object $session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'id' => $session->id,
                'platform' => $agent->platform(),
                'browser' => $agent->browser(),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->toIso8601String(),
            ];
        });
    }

    protected function createAgent(object $session): Agent
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }
}
