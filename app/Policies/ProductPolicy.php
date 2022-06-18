<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

//    /**
//     * Determine whether the user can update the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Product  $product
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function update(User $user, Product $product)
//    {
//        //
//    }

    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Product  $product
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function restore(User $user, Product $product)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Product  $product
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function forceDelete(User $user, Product $product)
//    {
//        //
//    }
}
