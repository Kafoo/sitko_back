<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Place;
use App\Models\Caldate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($place_id = null)
    {

        #Index by place

        if ($place_id) {
            return Place::find($place_id)->events()->with(['caldates', 'image'])->get();

        # Index all

        }else{
            return Event::with('place')->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $fail_message = trans('crud.fail.event.creation');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Creating Event

        try {

            $author_id = Auth::id();

            $newEvent = Event::create($request->all() + ['author_id' => $author_id]);
            $newEvent->load('place');
            $newEvent->load('author');

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }


        #  Creating related caldates

        try {

            $newEvent->storeCaldates($request->get('caldates'));

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.creation'));
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {                

                $newEvent->storeImage($request->image);

            } catch (\Exception $e) {

                return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.creation'));
            }
        }

        # Link/Create Tags

        try {

            $newEvent->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.creation'));
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.event.creation'),
            'event' => $newEvent
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return $event;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {

        $fail_message = trans('crud.fail.event.update');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Update event

        try {       

            $editedEvent = tap($event)->update($request->all());

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }
        
        # Update related image (Database + Cloudinary)

        try {                

            $hadImage = Image::where('imageable_id', $editedEvent->id)->count() > 0;

            //If new image exists
            if ($request->image) {
                //If old image exists
                if ($hadImage){
                    $editedEvent->image->change($request->image);
                }else{
                    $editedEvent->storeImage($request->image);
                }
            }else{

                $editedEvent->deleteImage();
            }
            

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.update'));
        }

        # Update related caldates

        if ($request->get('eventOnly') !== true) {

            try {
                
                if ($caldates = Caldate::where('child_id', $event->id)) {
                    $caldates->delete();
                }

                $editedEvent->storeCaldates($request->get('caldates'));

            } catch (\Exception $e) {

                return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.update'));
            }
            
        }

        # Update related tags

        try {

            $editedEvent->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.update'));
        }

        # Success

        DB::commit();
        return response()->json([
            'success' => trans('crud.success.event.update'),
            'event' => $editedEvent
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {

        $fail_message = trans('crud.fail.event.deletion');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Delete related caldates

        try {
            
            if ($caldates = Caldate::where('child_id', $event->id)) {
               $caldates->delete();
            }

        } catch (\Exception $e) {
            
            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.deletion'));

        }

        # Delete related image (Database + Cloudinary)

        try {
            
            $event->deleteImage();

        } catch (\Exception $e) {
            
            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.deletion'));

        }

        # Delete related tags

        try {

            $event->updateTags([]);


        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.deletion'));

        } 

        # Delete event

        try {
            
            $event->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.event.deletion'),
        ], 200);

    }

}
