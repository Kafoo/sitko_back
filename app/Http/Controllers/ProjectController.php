<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Place;
use App\Models\Caldate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

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
            return Place::find($place_id)->projects()->get();

        # Index all

        }else{
            return Project::with('place')->get();
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
        $this->transactionLevel = DB::transactionLevel();

        # Creating Project

        try {

            $author_id = Auth::id();

            $newProject = Project::create($request->all() + ['author_id' => $author_id]);
            $newProject->load('place');

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        #  Creating related caldates

        try {

            $newProject->storeCaldates($request->get('caldates'));

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.creation'));
        }
        
        # Uploading image to Cloudinary

        if ($request->image !== null) {

            try {                

                $newProject->storeImage($request->image);

            } catch (\Exception $e) {

                return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.creation'));
            }
        }

        # Link/Create Tags

        try {

            $newProject->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.creation'));
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
    public function show($id)
    {
        $project = Project::find($id);
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
        $this->transactionLevel = DB::transactionLevel();

        # Update project

        try {       

            $editedProject = tap($project)->update($request->all());

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }
        
        # Update related image (Database + Cloudinary)

        try {                

            $hadImage = Image::where('imageable_id', $editedProject->id)->count() > 0;

            //If new image exists
            if ($request->image) {
                //If old image exists
                if ($hadImage){
                    $editedProject->image->change($request->image);
                }else{
                    $editedProject->storeImage($request->image);
                }
            }else{

                $editedProject->deleteImage();
            }
            

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.update'));
        }

        # Update related caldates

        if ($request->get('projectOnly') !== true) {

            try {
                
                if ($caldates = Caldate::where('child_id', $project->id)) {
                    $caldates->delete();
                }

                $editedProject->storeCaldates($request->get('caldates'));

            } catch (\Exception $e) {

                return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.update'));
            }
            
        }
        
        # Update related tags

        try {

            $editedProject->updateTags($request->tags);

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.update'));
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
        $this->transactionLevel = DB::transactionLevel();

        # Delete related caldates

        try {
            
            if ($caldates = Caldate::where('child_id', $project->id)) {
               $caldates->delete();
            }

        } catch (\Exception $e) {
            
            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.caldates.deletion'));

        }

        # Delete related image (Database + Cloudinary)

        try {
            
            $project->deleteImage();

        } catch (\Exception $e) {
            
            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.image.deletion'));

        }

        # Delete related tags

        try {

            $project->updateTags([]);


        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message, trans('crud.fail.tags.deletion'));

        } 

        # Delete project

        try {
            
            $project->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.project.deletion'),
        ], 200);

    }

}
