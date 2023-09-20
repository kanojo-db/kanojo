<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MovieVersion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MovieVersionPolicy
{
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
    public function view(User $user, MovieVersion $movieVersion): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create movie');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MovieVersion $movieVersion): bool
    {
        return $user->can('edit movie');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MovieVersion $movieVersion): bool
    {
        return $user->can('delete movie');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MovieVersion $movieVersion): Response
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MovieVersion $movieVersion): Response
    {
        return Response::denyAsNotFound();
    }
}
