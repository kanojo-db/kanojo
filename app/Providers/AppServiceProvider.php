<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('count', function () {
            $raw = $this->engine()->search($this);

            return (int) $raw['nbHits'];
        });
    }
}
