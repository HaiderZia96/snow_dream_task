<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User|null  $user
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(?User $user): bool|Response
    {
        return true; // Public access
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User|null  $user
     * @param  \App\Models\Item  $item
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(?User $user, Item $item): bool|Response
    {
        if ($item->status === 'inactive' && (!$user || !$user->isAdmin())) {
            return Response::deny('This item is not available for viewing.');
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user): bool|Response
    {
        if (!$user->email_verified_at) {
            return Response::deny('You must verify your email address before creating items.');
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Item  $item
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Item $item): bool|Response
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id !== $item->owner_id) {
            return Response::deny('You do not have permission to update this item.');
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Item  $item
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Item $item): bool|Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can delete items.');
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Item  $item
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Item $item): bool|Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can restore items.');
        }

        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Item  $item
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Item $item): bool|Response
    {
        if (!$user->isAdmin()) {
            return Response::deny('Only administrators can permanently delete items.');
        }

        return true;
    }
}
