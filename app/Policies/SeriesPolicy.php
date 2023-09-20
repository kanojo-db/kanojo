<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Series;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SeriesPolicy
{
    public function before(?User $user): ?bool
    {
        if ($user === null) {
            return null;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Series $series): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create series');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Series $series): bool
    {
        return $user->can('edit series');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Series $series): bool
    {
        return $user->can('delete series');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Series $series): Response
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Series $series): Response
    {
        return Response::denyAsNotFound();
    }
}
