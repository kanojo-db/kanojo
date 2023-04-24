<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\ContentReport;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Studio;
use App\Policies\ContentReportPolicy;
use App\Policies\MoviePolicy;
use App\Policies\PersonPolicy;
use App\Policies\StudioPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Movie::class => MoviePolicy::class,
        Person::class => PersonPolicy::class,
        Studio::class => StudioPolicy::class,
        ContentReport::class => ContentReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

    }
}
