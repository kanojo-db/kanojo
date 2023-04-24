<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\MetadataLanguage;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Session;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $ziggy = new Ziggy($group = null, $request->url());

        return array_merge(parent::share($request), [
            'ziggy' => $ziggy->toArray(),
            'locale' => app()->getLocale(),
            'user' => function () use ($request) {
                if (! $request->user()) {
                    return null;
                }

                $request->user()->load('roles');

                return array_merge(
                    $request->user()->toArray(),
                    [
                        'two_factor_enabled' => ! is_null($request->user()->two_factor_secret),
                    ]
                );
            },
            '_token' => function () {
                return Session::token();
            },
            '_session' => function () {
                return Session::all();
            },
            'languages' => function () {
                return MetadataLanguage::all();
            },
        ]);
    }
}
