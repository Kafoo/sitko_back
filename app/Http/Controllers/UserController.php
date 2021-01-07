<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Image;

class UserController extends Controller
{

    public function index()
    {
        return User::with(['image'])->all();
    }

    public function update(Request $request, User $user)
    {

        $fail_message = trans('crud.fail.user.update');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Update project

        try {       

            $editedUser = tap($user)->update($request->all());

        } catch (\Exception $e) {

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


	public function destroy($userID)
	{
		return User::find($userID)->delete();
	}
}
