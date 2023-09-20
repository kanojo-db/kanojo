<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 */
trait HasPopularity
{
    /**
     * Registers a visit for the current page.
     */
    public function visit(): Visit
    {
        $clientIdentifier = implode('-', [request()->ip(), Carbon::now()->format('Y-m-d'), request()->userAgent()]);

        $clientHash = hash('sha256', $clientIdentifier);

        return $this->visits()->updateOrCreate(
            [
                'client_hash' => $clientHash,
                'visitable_id' => $this->id,
                'visitable_type' => (new ReflectionClass($this))->getName(),
                'date' => Carbon::now()->toDateString(),
            ]
        );
    }

    /**
     * Setting relationship
     *
     * @return MorphMany<Visit>
     */
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    /**
     * Return count of the visits in the last day
     */
    public function visitsDay(): int
    {
        return $this->visitsLast(1);
    }

    /**
     * Return count of the visits in the last 7 days
     */
    public function visitsWeek(): int
    {
        return $this->visitsLast(7);
    }

    /**
     * Return count of the visits in the last 30 days
     */
    public function visitsMonth(): int
    {
        return $this->visitsLast(30);
    }

    /**
     * Return the count of visits since system was installed
     */
    public function visitsForever(): int
    {
        return $this->visits()
            ->count();
    }

    /**
     * Filter by popularity.
     *
     * @param  Builder<TModelClass>  $query
     * @return Builder<TModelClass>
     */
    public function scopePopular(Builder $query)
    {
        return $query->orderBy('popularity', 'desc');
    }

    /**
     * Return the visits of the model in the last ($days) days
     */
    public function visitsLast(int $days): int
    {
        return $this->visits()
            ->where('date', '>=', Carbon::now()->subDays($days)->toDateString())
            ->count();
    }

    /**
     * Return the visits of the model in a given interval date
     */
    public function visitsBetween(string $from, string $to): int
    {
        return $this->visits()
            ->whereBetween('date', [$from, $to])
            ->count();
    }
}
