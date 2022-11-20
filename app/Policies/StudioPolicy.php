<?php

namespace App\Policies;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudioPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User  $user
     */
    public function viewAny(User $user): void
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Studio  $studio
     */
    public function view(User $user, Studio $studio): void
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User  $user
     */
    public function create(User $user): void
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Studio  $studio
     */
    public function update(User $user, Studio $studio): void
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Studio  $studio
     */
    public function delete(User $user, Studio $studio): void
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Studio  $studio
     */
    public function restore(User $user, Studio $studio): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Studio  $studio
     */
    public function forceDelete(User $user, Studio $studio): void
    {
        //
    }
}
