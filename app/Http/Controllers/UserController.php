<?php

namespace App\Http\Controllers;

use App\Http\Resources\OtherUserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Traits\Controllers\LinkableController;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use LinkableController;

    protected $model = User::class;

    public function index()
    {
        //
    }


    public function show(User $user)
    {
        return new OtherUserResource($user);
    }


    public function update(Request $request, User $user)
    {

        $fail_message = trans('crud.fail.user.update');

        $this->beginTransaction();

        # Update user

        try {       

            $editedUser = tap($user)->update([
                        'name'=> $request->name,
                        'last_name'=> $request->last_name,
                        'email'=> $request->email,
            ]);


        } catch (\Exception  $e) {

            return $this->returnOrThrow($e, $fail_message);
        }
        
        # Update password

        try {       

            if ($request->password) {
                $editedUser = tap($user)->update([
                            'name'=> $request->name,
                            'last_name'=> $request->last_name,
                            'email'=> $request->email,
                            'password'=> Hash::make($request->password)
                ]);
            }

        } catch (\Exception  $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Update related image (Database + Cloudinary)

        try {                

            $hadImage = Image::where('imageable_id', $editedUser->id)->count() > 0;

            //If new image exists
            if ($request->image) {
                //If old image exists
                if ($hadImage){
                    $editedUser->image->change($request->image);
                }else{
                    $editedUser->storeImage($request->image);
                }
            }else{

                $editedUser->deleteImage();
            }
            
        # Update related tags

        try {

            $editedUser->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.update'));
        }

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.update'));
        }

        # Success

        DB::commit();
        return response()->json([
            'success' => trans('crud.success.user.update'),
            'user' => $editedUser
        ], 200);
    }


	public function destroy(User $user)
	{

        $fail_message = trans('crud.fail.user.deletion');

        $this->beginTransaction();

        # Delete related image (Database + Cloudinary)

        try {
            
            $user->deleteImage();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.deletion'));
        }

        # Delete related tags

        try {

			foreach ($user->tags as $tag) {
                if ($tag->custom == '1') {
    				$tag->delete();
                }
			}

            $user->tags()->detach();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.deletion'));

        } 

        # Delete user

        try {
            
            $user->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.user.deletion'),
        ], 200);

	}
}
