<?php

namespace App\Http\Controllers;

use App\Models\Caldate;
use App\Models\Place;
use Illuminate\Http\Request;

class CaldateController extends Controller
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
            return Place::find($place_id)->caldates()->with(['child'])->get();

        # Index all

        }else{
            return Caldate::with('child')->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caldate  $caldate
     * @return \Illuminate\Http\Response
     */
    public function show(Caldate $caldate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caldate  $caldate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caldate $caldate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caldate  $caldate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caldate $caldate)
    {
        //
    }

}
