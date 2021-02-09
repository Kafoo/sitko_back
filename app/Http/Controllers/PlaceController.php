<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Place::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $fail_message = trans('crud.fail.place.creation');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Creating Place

        try {

            $author_id = Auth::id();

            $newPlace = Place::create($request->all() + ['author_id' => $author_id]);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {

                $newPlace->storeImage($request->image);

            } catch (\Exception $e) {

                return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.creation'));
            }
        }

        # Link/Create Tags

        try {

            $newPlace->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.creation'));
        }



        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.place.creation'),
            'place' => $newPlace
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show($placeId)
    {

        return Place::find($placeId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {

        $fail_message = trans('crud.fail.place.update');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Update place

        try {       

            $editedPlace = tap($place)->update($request->all());

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Update related image (Database + Cloudinary)

        try {                  

            $editedPlace->image->change($request->image);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.update'));
        }

        # Update related tags

        try {                  

            $editedPlace->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.update'));
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.place.update'),
            'place' => $editedPlace
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {

        $fail_message = trans('crud.fail.place.deletion');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Delete related image (Database + Cloudinary)

        try {
            
            $place->deleteImage();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.deletion'));
        }

        # Delete related projects

        try {

			$controller = new ProjectController;
			foreach ($place->projects as $project) {
				$controller->destroy($project);
			}

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.projects.deletion'));

        }

        # Delete related events

        try {

			$controller = new EventController;
			foreach ($place->events as $event) {
				$controller->destroy($event);
			}

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.events.deletion'));

        }

        # Delete related tags

        try {

			foreach ($place->tags as $tag) {
                if ($tag->custom == '1') {
    				$tag->delete();
                }
			}

            $place->tags()->detach();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.deletion'));

        } 

        # Delete place

        try {
            
            $place->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.place.deletion'),
        ], 200);

    }
}
