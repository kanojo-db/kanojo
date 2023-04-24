<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PersonPolicy
{
    use HandlesAuthorization;

    public function before(?User $user): ?bool
    {
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Person $person): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response|bool
    {
        return $user->can('create person');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Person $person): Response|bool
    {
        return $user->can('edit person');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Person $person): Response|bool
    {
        return $user->can('delete person');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Person $person): Response|bool
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Person $person): Response|bool
    {
        return Response::denyAsNotFound();
    }
}
