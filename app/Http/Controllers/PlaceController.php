<?php

namespace App\Http\Controllers;

use App\Models\Place;
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
        return Place::with('image')->get();
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

        # Creating Place

        try {

            $newPlace = Place::create($request->all());

        } catch (\Exception $e) {
            
            DB::rollback();
            return response()->json([
                'message' => $fail_message,
                'more' => $e->getMessage()
            ], 500);
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {                

                $newPlace->storeImage($request->image);

            } catch (\Exception $e) {

                DB::rollback();
                return response()->json([
                    'message' => $fail_message,
                    'info' => trans('crud.fail.image.creation')
                ], 500);
            }
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

        return Place::with('image')->find($placeId);
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

        # Update place

        try {       

            $editedPlace = tap($place)->update($request->all());

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => $fail_message,
            ], 500);
        }

        # Update related image (Database + Cloudinary)

        try {                  

            $editedPlace->updateImage($request->image);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => $fail_message,
                'info' => trans('crud.fail.image.update'),
                "more" => $e->getMessage()
            ], 500);
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
        //
    }
}
