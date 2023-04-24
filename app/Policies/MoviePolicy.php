<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MoviePolicy
{
    use HandlesAuthorization;

    public function before(?User $user): bool|null
    {
        if ($user === null) {
            return null;
        }

        if ($user?->isAdministrator()) {
            return true;
        }

        if ($user?->isBanned()) {
            return false;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Movie $movie): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('create movie');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Movie $movie): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('edit movie');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Movie $movie): Response|bool
    {
        return $user->can('delete movie');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Movie $movie): Response|bool
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Movie $movie): Response|bool
    {
        return Response::denyAsNotFound();
    }
}
