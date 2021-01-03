<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Place;
use App\Models\Event;
use Illuminate\Http\Request;
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

       $fail_message = trans('crud.fail.project.creation');

        DB::beginTransaction();

        # Creating Project

        try {

            $newProject = Project::create($request->all());

        } catch (\Exception $e) {
            
            DB::rollback();
            return response()->json([
                'message' => $fail_message,
            ], 500);
        }


        #  Creating related events

        try {

            $newProject->storeEvents($request->get('events'));

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => $fail_message,
                'info' => trans('crud.fail.events.creation')
            ], 500);
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {                

                $newProject->storeImage($request->image);

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
            'success' => trans('crud.success.project.creation'),
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

        $fail_message = trans('crud.fail.project.update');

        DB::beginTransaction();

        # Update project

        try {       

            $editedProject = tap($project)->update($request->all());

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => $fail_message,
            ], 500);
        }

        # Update related image (Database + Cloudinary)

        try {                
            //If new image exists
            if ($request->image) {
                //If old image exists
                if ($editedProject->image){
                    $editedProject->image->change($request->image);
                }else{
                    $editedProject->storeImage($request->image);
                }
            }else{
                $editedProject->deleteImage();
            }
            print_r($editedProject);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => $fail_message,
                'info' => trans('crud.fail.image.update'),
                'more' => $e->getMessage()
            ], 500);
        }

        # Update related events

        if ($request->get('projectOnly') !== true) {

            try {
                
                if ($events = Event::where('child_id', $project->id)) {
                    $events->delete();
                }

                $editedProject->storeEvents($request->get('events'));

            } catch (\Exception $e) {

                DB::rollback();
                return response()->json([
                    'message' => $fail_message,
                    'info' => trans('crud.fail.events.update')
                ], 500);
            }
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.project.update'),
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

        $fail_message = trans('crud.fail.project.deletion');

        DB::beginTransaction();

        # Delete related events

        try {
            
            if ($events = Event::where('child_id', $project->id)) {
               $events->delete();
            }

        } catch (\Exception $e) {
            
            DB::rollback();
            return response()->json([
                'message' => $fail_message,
                'info' => trans('crud.fail.events.deletion')
            ], 500);
        }

        # Delete related image (Database + Cloudinary)

        try {
            
            $project->deleteImage();

        } catch (\Exception $e) {
            
            DB::rollback();
            return response()->json([
                'message' => $fail_message,
                'info' => trans('crud.fail.image.deletion')
            ], 500);
        }

        # Delete project

        try {
            
            $project->delete();

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => $fail_message
            ], 500);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.project.deletion'),
        ], 200);

    }

}
