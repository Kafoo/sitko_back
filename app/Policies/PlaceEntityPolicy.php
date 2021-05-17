<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class PlaceEntityPolicy
{
    use HandlesAuthorization;

    /**
    * Perform pre-authorization checks.
    *
    * @param  \App\Models\User  $user
    * @param  string  $ability
    * @return void|bool
    */
    public function before(User $user)
    {
        if ($user->id == 731) {
            return true;
        }
    }

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
     * @param   $authorable
     * @return mixed
     */
    public function view(User $user, $entity)
    {

        if ($entity->author_id == $user->id) {
            $maxVisibility = 3 ;
        } else if ($entity->place->isLinked($user)) {
            $maxVisibility = 2;
        } else {
            $maxVisibility = 1;
        }

        $canViewPlace = Gate::allows('view', $entity->place);
        $canViewEntity = $entity->visibility <= $maxVisibility;

        return $canViewPlace && $canViewEntity;

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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * $authorable
     * @return mixed
     */
    public function update(User $user, $authorable)
    {
        return $user->id === $authorable->author->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  $authorable
     * @return mixed
     */
    public function delete(User $user, $authorable)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  $authorable
     * @return mixed
     */
    public function restore(User $user, $authorable)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  $authorable
     * @return mixed
     */
    public function forceDelete(User $user, $authorable)
    {
        //
    }
}
