<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

class ProjectController extends ProjentController
{
 
    public $projent_type = 'project';
    public $resource = ProjectResource::class;

    public $model;

    public function __construct()
    {
        $this->model = new Project;
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
    public function store(ProjectRequest $request)
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
    public function update(ProjectRequest $request, Project $project)
    {

        return $this->projentUpdate($request, $project);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $projent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        return $this->projentDestroy($project);

    }


}
