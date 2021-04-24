<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class ProjentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projentIndex($request, $place_id = null)
    {

        #Index by place

        $entities = $this->projent_type.'s';

        $projents = [];

        if ($place_id) {

            $place = Place::find($place_id);

            if ($request->filter == 'incoming') {

                $projents = $this->incoming($place->$entities())->get();

            }else{

                $projents = $place->$entities()->get();
            }

        # Index all

        }else{

            if ($request->filter == 'incoming') {

                $projents = $this->incoming($this->model->with('place'))->get();

            }else{
            
                $projents = $this->model->with('place')->get();
            }
        }

        $projents = $this->visibilityFilter($projents);

        return $this->resource::collection($projents);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function projentStore($request)
    {

        DB::beginTransaction();

        try {

            $newProjent = $this->model::create($request->all());

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.'.$this->projent_type.'.creation'));
        }

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

        Gate::authorize('view', $projent);

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

        DB::beginTransaction();

        try {       

            tap($projent)->update($request->all());

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.'.$this->projent_type.'.update'));
        }

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.'.$this->projent_type.'.update'),
            $this->projent_type => new $this->resource($projent)
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

        DB::beginTransaction();

        try {
            
            $projent->delete();

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, trans('crud.fail.'.$this->projent_type.'.deletion'));
        }

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
