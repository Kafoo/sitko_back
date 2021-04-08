<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     * @return mixed
     */
    public function view(User $user, Place $place)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the place.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     * @return mixed
     */
    public function update(User $user, Place $place)
    {
    
        return $user->id === $place->author_id;

    }

    /**
     * Determine whether the user can join the place.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     * @return mixed
     */
    public function link(User $user, Place $place)
    {
        return true;
        return $user->id !== $place->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     * @return mixed
     */
    public function delete(User $user, Place $place)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     * @return mixed
     */
    public function restore(User $user, Place $place)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Place  $place
     * @return mixed
     */
    public function forceDelete(User $user, Place $place)
    {
        //
    }
}
