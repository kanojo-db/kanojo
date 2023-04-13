<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Jenssegers\Agent\Agent;
use Session;

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
     * @return \Illuminate\Support\Collection<(int|string), array{id: mixed, platform: bool|string, browser: bool|string, ip_address: mixed, is_current_device: bool, last_active: string}>
     */
    public function sessions(Request $request): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        /** @var User */
        $user = $request->user();

        $sessions = DB::table((string) config('session.table', 'sessions'))
                ->where('user_id', $user->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get();

        return collect($sessions)
                ->map(function ($session) use ($request) {
                    $agent = $this->createAgent($session);

                    // TODO: Replace this with a proper, typed object.
                    return [
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
