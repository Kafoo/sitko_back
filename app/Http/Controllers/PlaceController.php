<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\Place;
use App\Http\Resources\PlaceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        $places = $this->visibilityFilter(Place::all());

        return PlaceResource::collection($places);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceRequest $request)
    {

        DB::beginTransaction();

        try {

            $newPlace = Place::create($request->all());

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.place.creation'));
        }

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
        $place = Place::find($placeId);

        Gate::authorize('view', $place);

        return new PlaceResource($place);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceRequest $request, Place $place)
    {

        DB::beginTransaction();

        try {       

            $editedPlace = tap($place)->update($request->all());

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.place.update'));
        }

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

        DB::beginTransaction();

        try {
            
            $place->delete();

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.place.deletion'));
        }

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.place.deletion'),
        ], 200);

    }

}
