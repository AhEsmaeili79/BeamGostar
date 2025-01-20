<?php

namespace App\Policies;

use App\Models\User;
use App\Models\get_answers;
use Illuminate\Auth\Access\HandlesAuthorization;

class get_answersPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_get::answers');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, get_answers $getAnswers): bool
    {
        return $user->can('view_get::answers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_get::answers');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, get_answers $getAnswers): bool
    {
        return $user->can('update_get::answers');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, get_answers $getAnswers): bool
    {
        return $user->can('delete_get::answers');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_get::answers');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, get_answers $getAnswers): bool
    {
        return $user->can('force_delete_get::answers');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_get::answers');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, get_answers $getAnswers): bool
    {
        return $user->can('restore_get::answers');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_get::answers');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, get_answers $getAnswers): bool
    {
        return $user->can('replicate_get::answers');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_get::answers');
    }
}
