<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $place_id)
    {

        $notes = Place::find($place_id)->notes()->get();

        $notes = $this->visibilityFilter($notes);

        return NoteResource::collection($notes);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
       $fail_message = trans('crud.fail.note.creation');

        DB::beginTransaction();

        # Creating Note

        try {

            $newNote = Note::create($request->all() + ['author_id' => Auth::id()]);

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.note.creation'),
            'note' => new NoteResource($newNote)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Note::find($id);

        Gate::authorize('view', $note);

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request, Note $note)
    {

        $fail_message = trans('crud.fail.note.update');

        DB::beginTransaction();

        # Update note

        try {       

            $editedNote = tap($note)->update($request->all());

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, $fail_message);
        }

        # Success

        DB::commit();
        return response()->json([
            'success' => trans('crud.success.note.update'),
            'note' => new NoteResource($editedNote)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {

        $fail_message = trans('crud.fail.note.deletion');

        DB::beginTransaction();

        # Delete note

        try {
            
            $note->delete();

        } catch (\Exception $e) {

            return $this->exceptionResponse($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.note.deletion'),
        ], 200);

    }
}
