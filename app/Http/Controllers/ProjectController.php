<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Event;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::with('events')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $newProject = Project::create($request->all());

        $events = [];

        foreach ($request->get('events') as $event) {
            $eventModel = new Event($event);
            $eventModel->type('project');
            $events[] = $eventModel;
        }

        $newEvents = $newProject->events()->saveMany($events);

        $newProject->events = $newProject->events->all(); 

        if($newProject){

            return response()->json([
                'success' => 'Projet créé avec succès',
                'project' => $newProject
            ], 200);

        };

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {

        $editedProject = tap($project)->update($request->all());

        $events = Event::where('child_id', $project->id);
        if ($events) {
            $destroyEvents = $events->delete();
        }

        $newEvents = [];

        foreach ($request->get('events') as $event) {
            $eventModel = new Event($event);
            $eventModel->type('project');
            $newEvents[] = $eventModel;
        }

        $editedProject->events = $project->events()->saveMany($newEvents);

        if($editedProject){

            return response()->json([
                'success' => 'Projet modifié avec succès',
                'project' => $editedProject
            ], 200);

        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        $events = Event::where('child_id', $project->id);
        if ($events) {
            $destroyEvents = $events->delete();
        }

        $destroyProject = $project->delete();

        if($destroyProject){

            return response()->json([
                'success' => 'Projet supprimé avec succès'
            ], 200);

        };
    }

}
