<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductRetailer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductRetailerPolicy
{
    use HandlesAuthorization;

//    /**
//     * Determine whether the user can view any models.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function viewAny(User $user)
//    {
//        //
//    }
//

    public function view(User $user, ProductRetailer $productRetailer): bool
    {
        return $user->id === $productRetailer->product->user_id;
    }

    public function create(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

//    /**
//     * Determine whether the user can update the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\ProductRetailer  $productRetailer
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function update(User $user, ProductRetailer $productRetailer)
//    {
//        //
//    }

    public function delete(User $user, ProductRetailer $productRetailer): bool
    {
        return $user->id === $productRetailer->product->user_id;
    }

//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\ProductRetailer  $productRetailer
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function restore(User $user, ProductRetailer $productRetailer)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\ProductRetailer  $productRetailer
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function forceDelete(User $user, ProductRetailer $productRetailer)
//    {
//        //
//    }
}
