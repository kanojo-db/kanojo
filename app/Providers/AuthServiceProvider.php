<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\ContentReport;
use App\Models\KanojoMedia;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Studio;
use App\Models\User;
use App\Policies\ContentReportPolicy;
use App\Policies\MediaPolicy;
use App\Policies\MoviePolicy;
use App\Policies\PersonPolicy;
use App\Policies\StudioPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        KanojoMedia::class => MediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function (?User $user, $ability) {
            // If the user is banned, they can't do anything.
            if (optional($user)->hasRole('banned')) {
                return false;
            }

            // Admins can do anything, so we don't need to check for anything else.
            return optional($user)->hasRole('admin') ? true : null;
        });
    }
}
