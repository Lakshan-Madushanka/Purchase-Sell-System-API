<?php

namespace App\Policies;

use App\Traits\AdminActions;
use App\User;
use App\seller;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
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
     * @param  \App\seller  $seller
     * @return mixed
     */
    public function view(User $user, seller $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can sell something.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function sell(User $user, Seller $seller)
    {
        return $user->id === $seller->id;

    }

    /**
     * Determine whether the user can edit product.
     *
     * @param  \App\User  $user
     * @param  \App\seller  $seller
     * @return mixed
     */
    public function editProduct(User $user, seller $seller)
    {
        return $user->id === $seller->id;

    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\User  $user
     * @param  \App\seller  $seller
     * @return mixed
     */
    public function deleteProduct(User $user, seller $seller)
    {
        return $user->id === $seller->id;

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\seller  $seller
     * @return mixed
     */
    public function restore(User $user, seller $seller)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\seller  $seller
     * @return mixed
     */
    public function forceDelete(User $user, seller $seller)
    {
        //
    }
}
