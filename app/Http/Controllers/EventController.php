<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends ProjentController
{
 
    public $projent_type = 'event';
    public $resource = EventResource::class;

    public $model;

    public function __construct()
    {
        $this->model = new Event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $place_id = null)
    {
    
        return $this->projentIndex($request, $place_id);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return $this->projentStore($request);

    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->projentShow($id);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {

        return $this->projentUpdate($request, $event);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {

        return $this->projentDestroy($event);

    }

}
