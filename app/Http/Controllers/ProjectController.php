<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Place;
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
    public function index($place_id = null)
    {

        #Index by place

        if ($place_id) {
            return Place::find($place_id)->projects()->with(['events', 'image'])->get();

        # Index all

        }else{
            return Project::with(['events', 'image'])->get();
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

        DB::beginTransaction();

        # Creating Project

        try {

            $newProject = Project::create($request->all());

        } catch (\Exception $e) {
            
            DB::rollback();
            abort(500, "le projet n'a pas pu être créé"); 
        }

        #  Creating related events

        try {

            $newProject->storeEvents($request->get('events'));

        } catch (\Exception $e) {

            DB::rollback();
            abort(500, $e->getMessage()); 
            abort(500, "les événements n'ont pas pu être créés"); 
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {                

                $newProject->storeImage($newProject, $request->image);

            } catch (\Exception $e) {

                DB::rollback();
                abort(500, $e->getMessage()); 
                abort(500, "l'image n'a pas pu être téléchargée"); 
            }
        }

        # Reponse

        DB::commit();

        return response()->json([
            'success' => 'Projet créé avec succès',
            'project' => $newProject
        ], 200);
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

        DB::beginTransaction();

        $errors = [];

        # Update project

        try {       

            $editedProject = tap($project)->update($request->all());

        } catch (\Exception $e) {

            DB::rollback();
            abort(500, "le projet n'a pas pu être modifié"); 
        }

        # Update related image (Database + Cloudinary)

        try {                

            if ($request->imageChanged === true) {

                // Deleting old image

                $editedProject->deleteImageIfExists();;

                // Storing new image
                if ($request->image !== null) {           

                    $editedProject->storeImage($request->image);

                }

            }else{
                $editedProject->image = $request->image;
            }

        } catch (\Exception $e) {

            DB::rollback();
            abort(500, "l'image n'a pas pu être modifiée"); 
        }

        # Update related events

        if ($request->get('projectOnly') !== true) {

            try {
                
                $events = Event::where('child_id', $project->id);
                if ($events) {
                    $destroyEvents = $events->delete();
                }

                $editedProject->storeEvents($request->get('events'));

            } catch (\Exception $e) {

                DB::rollback();
                abort(500, "les événements n'ont pas pu être modifiés"); 

            }
        }

        # Reponse

        DB::commit();

        return response()->json([
            'success' => 'Projet modifié avec succès',
            'project' => $editedProject
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        DB::beginTransaction();

        # Delete related events

        try {
            
            $events = Event::where('child_id', $project->id);
            if ($events) {
                $destroyEvents = $events->delete();
            }

        } catch (\Exception $e) {
            
            DB::rollback();
            abort(500, "les événements n'ont pas pu être supprimés"); 
        }

        # Delete related image (Database + Cloudinary)

        try {
            
            $project->deleteImageIfExists();

        } catch (\Exception $e) {
            
            DB::rollback();
            abort(500, $e->getMessage()); 
            abort(500, "l'image n'a pas pu être supprimée"); 
        }

        # Delete project

        try {
            
            $destroyProject = $project->delete();

        } catch (\Exception $e) {

            DB::rollback();
            abort(500, "le projet n'a pas pu être supprimé"); 
        }

        # Reponse

        DB::commit();

        return response()->json([
            'success' => 'Projet supprimé avec succès',
        ], 200);

    }

}
