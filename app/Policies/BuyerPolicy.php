<?php

namespace App\Policies;

use App\User;
use App\buyer;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class BuyerPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\buyer  $buyer
     * @return mixed
     */
    public function view(User $user, buyer $buyer)
    {
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can purchase something.
     *
     * @param  \App\User  $user
     * @param  \App\buyer  $buyer
     * @return mixed
     */
    public function purchase(User $user, buyer $buyer)
    {
        return $user->id === $buyer->id;

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\buyer  $buyer
     * @return mixed
     */
    public function delete(User $user, buyer $buyer)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\buyer  $buyer
     * @return mixed
     */
    public function restore(User $user, buyer $buyer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\buyer  $buyer
     * @return mixed
     */
    public function forceDelete(User $user, buyer $buyer)
    {
        //
    }
}
