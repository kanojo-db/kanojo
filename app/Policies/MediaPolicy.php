<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\KanojoMedia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MediaPolicy
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
    public function view(User $user, KanojoMedia $kanojoMedia): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create media');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KanojoMedia $kanojoMedia): bool
    {
        return $user->can('edit media');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KanojoMedia $kanojoMedia): bool
    {
        return $user->can('delete media');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KanojoMedia $kanojoMedia): Response
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KanojoMedia $kanojoMedia): Response
    {
        return Response::denyAsNotFound();
    }
}
