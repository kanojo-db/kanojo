<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ContentReport;
use App\Models\MovieVersion;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        Route::model('report', ContentReport::class);
        Route::model('version', MovieVersion::class);

        $this->routes(function () {
            // If running dev, expose the API routes in the /api/ directory. Otherwise, expose them at api.kanojo.app
            if (config('app.env') === 'local') {
                Route::prefix('api')
                    ->middleware('api')
                    ->middleware('ifMatch')
                    ->middleware('ifNoneMatch')
                    ->group(base_path('routes/api.php'));
            } else {
                Route::domain('api.kanojodb.com')
                    ->middleware('api')
                    ->middleware('ifMatch')
                    ->middleware('ifNoneMatch')
                    ->group(base_path('routes/api.php'));
            }

            Route::domain('developer.'.config('app.url'))
                ->middleware(config('scribe.laravel.middleware', []))
                ->group(base_path('routes/developer.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::model('media', \App\Models\KanojoMedia::class);
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
