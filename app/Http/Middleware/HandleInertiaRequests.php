<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'locale' => app()->getLocale(),
            'user' => function () use ($request) {
                if (!$request->user()) {
                    return;
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
        ]);
    }
}
