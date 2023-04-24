<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StudioPolicy
{
    use HandlesAuthorization;

    public function before(User $user): bool|null
    {
        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->isBanned()) {
            return false;
        }

        return null;
    }
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Studio $studio): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('create studio');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Studio $studio): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('edit studio');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Studio $studio): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('delete studio');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Studio $studio): Response|bool
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Studio $studio): Response|bool
    {
        return Response::denyAsNotFound();
    }
}
