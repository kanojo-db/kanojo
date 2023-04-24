<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ContentReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ContentReportPolicy
{
    use HandlesAuthorization;

    public function before(?User $user): bool|null
    {
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
    public function viewAny(User $user): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContentReport $contentReport): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('create content report');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContentReport $contentReport): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('edit content report');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContentReport $contentReport): Response|bool
    {
        return $user->hasVerifiedEmail() && $user->can('delete content report');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContentReport $contentReport): Response|bool
    {
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContentReport $contentReport): Response|bool
    {
        return Response::denyAsNotFound();
    }
}
