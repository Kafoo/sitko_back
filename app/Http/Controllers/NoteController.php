<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $place_id)
    {

        return Place::find($place_id)->notes()->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $fail_message = trans('crud.fail.note.creation');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Creating Note

        try {

            $author_id = Auth::id();

            $newNote = Note::create($request->all() + ['author_id' => $author_id]);
            $newNote->load('place');
            $newNote->load('author');

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.note.creation'),
            'note' => $newNote
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Note::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {

        $fail_message = trans('crud.fail.note.update');

        DB::beginTransaction();
        $this->transactionLevel = DB::transactionLevel();

        # Update note

        try {       

            $editedNote = tap($note)->update($request->all());

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();
        return response()->json([
            'success' => trans('crud.success.note.update'),
            'note' => $editedNote
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
        $this->transactionLevel = DB::transactionLevel();

        # Delete note

        try {
            
            $note->delete();

        } catch (\Exception $e) {

            return $this->returnOrThrow($e, $fail_message);
        }

        # Success

        DB::commit();

        return response()->json([
            'success' => trans('crud.success.note.deletion'),
        ], 200);

    }
}
