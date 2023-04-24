<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Crawler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgeGateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response
    {
        if (Crawler::isCrawler()) {
            return $next($request);
        }

        $ageCookie = $request->cookie('age_verified');

        if (! $ageCookie && $request->path() !== 'age-check') {
            return redirect()->guest(route('age_gate.index'));
        }

        return $next($request);
    }
}
