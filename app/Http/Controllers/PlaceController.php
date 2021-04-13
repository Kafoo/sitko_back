<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Http\Resources\PlaceResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{

    protected $model = Place::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PlaceResource::collection(Place::all());
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

        $this->beginTransaction();

        # Creating Place

        try {

            $author_id = Auth::id();

            $newPlace = Place::create($request->all() + ['author_id' => $author_id]);
            $newPlace->load('author');


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
            'place' => new PlaceResource($newPlace)
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

        return new PlaceResource(Place::find($placeId));
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

        $this->beginTransaction();

        # Update place

        try {       

            $editedPlace = tap($place)->update($request->all());

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Update related image (Database + Cloudinary)

        try {                  

            $hadImage = Image::where('imageable_id', $editedPlace->id)->count() > 0;

            //If new image exists
            if ($request->image) {
                //If old image exists
                if ($hadImage){
                    $editedPlace->image->change($request->image);
                }else{
                    $editedPlace->storeImage($request->image);
                }
            }else{

                $editedPlace->deleteImage();
            }

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
            'place' => new PlaceResource($editedPlace)
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

        $this->beginTransaction();

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

        # Delete related notes

        try {

			$controller = new NoteController;
			foreach ($place->notes as $note) {
				$controller->destroy($note);
			}

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.notes.deletion'));

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
