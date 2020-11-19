<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Event;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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


        $requestProject = $request->all();

        # Uploading img to CLoudinary

        if ($request->image !== null) {

            $cloudResponse = Cloudinary::upload($request->image);
            $requestProject['img'] = $cloudResponse->getSecurePath();
            $parts = explode('upload/', $requestProject['img']);
            $requestProject['img_medium'] = $parts[0].'upload/t_medium/'.$parts[1];
            $requestProject['img_thumb'] = $parts[0].'upload/t_thumb/'.$parts[1];

        }

        # Creating Project

        $newProject = Project::create($requestProject);

        #  Creating related events

        $events = [];

        foreach ($request->get('events') as $event) {
            $eventModel = new Event($event);
            $eventModel->type('project');
            $events[] = $eventModel;
        }

        $newEvents = $newProject->events()->saveMany($events);

        # Response

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

        if ($request->get('projectOnly') !== true) {

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
        }


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
