<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->isProduction()) {
            $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->prependMiddleware(\Spatie\Csp\AddCspHeaders::class);
        }
    }
}
