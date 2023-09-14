<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\ContentReportCreated;
use App\Events\MovieVersionUpdated;
use App\Listeners\AgeUpdateSubscriber;
use App\Listeners\ReactionAdded;
use App\Listeners\ReactionRemoved;
use App\Listeners\SendNewContentReportNotification;
use App\Listeners\UpdateMovieReleaseDate;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ContentReportCreated::class => [
            SendNewContentReportNotification::class,
        ],
        MovieVersionUpdated::class => [
            UpdateMovieReleaseDate::class,
        ],
        ReactionHasBeenAdded::class => [
            ReactionAdded::class,
        ],
        ReactionHasBeenRemoved::class => [
            ReactionRemoved::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array<int, string>
     */
    protected $subscribe = [
        AgeUpdateSubscriber::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {

    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
