<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

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
     */
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    /**
     * Return count of the visits in the last day
     *
     * @return mixed
     */
    public function visitsDay()
    {
        return $this->visitsLast(1);
    }

    /**
     * Return count of the visits in the last 7 days
     *
     * @return mixed
     */
    public function visitsWeek()
    {
        return $this->visitsLast(7);
    }

    /**
     * Return count of the visits in the last 30 days
     *
     * @return mixed
     */
    public function visitsMonth()
    {
        return $this->visitsLast(30);
    }

    /**
     * Return the count of visits since system was installed
     *
     * @return mixed
     */
    public function visitsForever()
    {
        return $this->visits()
            ->count();
    }

    /**
     * Filter by popularity.
     *
     * @return mixed
     */
    public function scopePopular($query)
    {
        return $query->orderBy('popularity', 'desc');
    }

    /**
     * Return the visits of the model in the last ($days) days
     */
    public function visitsLast($days): int
    {
        return $this->visits()
            ->where('date', '>=', Carbon::now()->subDays($days)->toDateString())
            ->count();
    }

    /**
     * Return the visits of the model in a given interval date
     */
    public function visitsBetween($from, $to): int
    {
        return $this->visits()
            ->whereBetween('date', [$from, $to])
            ->count();
    }
}
