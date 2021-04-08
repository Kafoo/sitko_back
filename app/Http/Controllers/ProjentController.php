<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Caldate;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProjentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projentIndex($request, $place_id)
    {

        #Index by place

        $entities = $this->projent_type.'s';


        if ($place_id) {

            if ($request->filter == 'incoming') {

                return $this->resource::collection($this->incoming(Place::find($place_id)->$entities())->get());

            }else{
              return $this->resource::collection(Place::find($place_id)->$entities()->get());
            }


        # Index all

        }else{

            if ($request->filter == 'incoming') {
            
              return $this->resource::collection($this->incoming($this->model->with('place'))->get());

            }else{
              return $this->resource::collection($this->model->with('place')->get());
            }

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function projentStore($request)
    {

       $fail_message = trans('crud.fail.'.$this->projent_type.'.creation');

        $this->beginTransaction();

        # Creating Project

        try {

            $author_id = Auth::id();

            $newProjent = $this->model->create($request->all() + ['author_id' => $author_id]);
            $newProjent->load('place');
            $newProjent->load('author');

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        #  Creating related caldates

        try {

            $newProjent->storeCaldates($request->get('caldates'));

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.creation'));
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {                

                $newProjent->storeImage($request->image);

            } catch (\Exception $e) {

                return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.creation'));
            }
        }

        # Link/Create Tags

        try {

            $newProjent->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.creation'));
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.'.$this->projent_type.'.creation'),
            $this->projent_type => new $this->resource($newProjent)
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projentShow($id)
    {
        $projent = $this->model->find($id);
        return new $this->resource($projent);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function projentUpdate($request, $projent)
    {

        $fail_message = trans('crud.fail.'.$this->projent_type.'.update');

        $this->beginTransaction();

        # Update project

        try {       

            $editedProjent = tap($projent)->update($request->all());

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }
        
        # Update related image (Database + Cloudinary)

        try {                

            $hadImage = Image::where('imageable_id', $editedProjent->id)->count() > 0;

            //If new image exists
            if ($request->image) {
                //If old image exists
                if ($hadImage){
                    $editedProjent->image->change($request->image);
                }else{
                    $editedProjent->storeImage($request->image);
                }
            }else{

                $editedProjent->deleteImage();
            }
            

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.update'));
        }

        # Update related caldates

        try {
            
            if ($caldates = Caldate::where('child_id', $projent->id)) {
                $caldates->delete();
            }

            $editedProjent->storeCaldates($request->get('caldates'));

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.update'));
        }
            
        # Update related tags

        try {

            $editedProjent->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.update'));
        }

        # Success

        DB::commit();
        return response()->json([
            'success' => trans('crud.success.'.$this->projent_type.'.update'),
            $this->projent_type => new $this->resource($editedProjent)
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function projentDestroy($projent)
    {

        $fail_message = trans('crud.fail.'.$this->projent_type.'.deletion');

        $this->beginTransaction();

        # Delete related caldates

        try {
            
            if ($caldates = Caldate::where('child_id', $projent->id)) {
               $caldates->delete();
            }

        } catch (\Exception $e) {
            
            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.deletion'));

        }

        # Delete related image (Database + Cloudinary)

        try {
            
            $projent->deleteImage();

        } catch (\Exception $e) {
            
            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.deletion'));

        }

        # Delete related tags

        try {

            $projent->updateTags([]);


        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.deletion'));

        } 

        # Delete project

        try {
            
            $projent->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.'.$this->projent_type.'.deletion'),
        ], 200);

    }

    private function incoming($projents){

        return $projents->whereHas('caldates', function ($query) {
            $query->where('start', '>', Carbon::now()->toDateTimeString());
        });
    }

}
