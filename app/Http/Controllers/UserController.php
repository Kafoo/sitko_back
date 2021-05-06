<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\QueryFilters\UserFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    protected $model = User::class;

    public function index(UserFilters $filters)
    {
        $places = $this->visibilityFilter(User::filterBy($filters)->get());

        return UserResource::collection($places);

    }


    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return new UserResource($user);

    }


    public function update(UserRequest $request, User $user)
    {

        DB::beginTransaction();

        try {       

            tap($user)->update($request->all());

        } catch (\Exception  $e) {

            return $this->exceptionResponse($e, trans('crud.fail.user.update'));
        }

        DB::commit();
        return response()->json([
            'success' => trans('crud.success.user.update'),
            'auth' => new AuthResource($user),
            'user' => new UserResource($user)
        ], 200);
    }


	public function destroy(User $user)
	{

        DB::beginTransaction();

        # Delete user

        try {
            
            $user->delete();

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.user.deletion'));
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.user.deletion'),
        ], 200);

	}
}
