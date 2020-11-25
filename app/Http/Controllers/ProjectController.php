<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Event;
use App\Models\Image;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::with(['events', 'image'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $response = DB::transaction(function () use ($request) {

            $errors = [];

            # Creating Project

            $newProject = Project::create($request->all());

            #  Creating related events

            $events = [];

            try {
                foreach ($request->get('events') as $event) {
                    $eventModel = new Event($event);
                    $eventModel->type('project');
                    $events[] = $eventModel;
                }

                $newEvents = $newProject->events()->saveMany($events);

                $newProject->events = $newProject->events->all(); 

            } catch (\Exception $e) {

                $errors['image'] = "l'image n'a pas pu être téléchargée";   
            }
            
            # Uploading image to Cloudinary

            if ($request->image !== null) {

                try {                

                    $cloudinary = Cloudinary::upload($request->image);
                    $imageModel = new Image();
                    $imageModel->fill($cloudinary);
                    $newProject->image = $newProject->image()->save($imageModel);

                } catch (\Exception $e) {

                    $errors['image'] = "l'image n'a pas pu être ajoutée au projet";
                    $errors['image_source'] = $e->getMessage();
                }
            }

            # Reponse

            if (count($errors) > 0) {

                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 500);

            }else{

                return response()->json([
                    'success' => 'Projet créé avec succès',
                    'project' => $newProject
                ], 200);
            }
        });

        return $response;

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

        # Update project

        $editedProject = tap($project)->update($request->all());

        # Update related image (Database + Cloudinary)

        if ($request->imageChanged === true) {

            // Deleting old image
            $image = Image::where('imageable_id', $editedProject->id);

            if (count($image->get()) > 0) {

                Cloudinary::destroy($image->get()[0]->public_id);
                $destroyImage = $image->delete();
            }

            // Storing new image
            if ($request->image !== null) {

                $cloudResponse = Cloudinary::upload($request->image);

                $imageModel = new Image();

                $imageModel->full = $cloudResponse->getSecurePath();
                $parts = explode('upload/', $imageModel->full);
                $imageModel->medium = $parts[0].'upload/t_medium/'.$parts[1];
                $imageModel->low_medium = $parts[0].'upload/t_low_medium/'.$parts[1];
                $imageModel->thumb = $parts[0].'upload/t_thumb/'.$parts[1];
                $imageModel->public_id = $cloudResponse->getPublicId();

                $editedProject->image = $editedProject->image()->save($imageModel);
            }
        }else{
            $editedProject->image = $request->image;
        }

        # Update related events

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

        # Response

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

        # Delete related events

        $events = Event::where('child_id', $project->id);
        if ($events) {
            $destroyEvents = $events->delete();
        }

        # Delete related image (Database + Cloudinary)

        $image = Image::where('imageable_id', $project->id);
        if (count($image->get()) > 0) {
            Cloudinary::destroy($image->get()[0]->public_id);
            $destroyImage = $image->delete();
        }

        # Delete project

        $destroyProject = $project->delete();

        # Response

        if($destroyProject){

            return response()->json([
                'success' => 'Projet supprimé avec succès'
            ], 200);

        };
    }

}
