<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get the logged in user's id
        /* $userId = Auth::user()->id; */
        //Using id() helper method from Auth facade
/*         $userId = Auth::id();

        //Using eloquent to get all the notes from the user chained with get method
        $notes = Note::where('user_id', $userId)->get(); */
        //use latest() to get latest inserted notes, use updated_at to get latest updated note instead of created so that it does order by latest updated notes
        /* $notes = Note::where('user_id', Auth::id())->latest('updated_at')->get(); */
        //Count of items per page can  be passed inside paginate() method , which is used for pagination
        //$notes = Note::where('user_id', Auth::id())->latest('updated_at')->paginate(5);
/*         $notes->each(function($note){
            dump($note->title);

        }); */
        //dd($notes);

        //$notes = Auth::user()->notes()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Return view notes.create
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Insert row to database
        //Validate request before inserting into database, if validation passes code will execute normally else print error in create.blade.php
        $request->validate(
            [
                'title' => 'required|max:120',
                'text' => 'required'
            ]
        );

/*         $note = new Note([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'text' => $request->text
        ]);

        $note->save(); */

        Auth::user()->notes()->create([
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'text' => $request->text
        ]);

        //return to index route notes.index
        return to_route('notes.index');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
        //$note = Note::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
         //Prevent unauthorised access and return 403 error on attempt, but recommended method is gate policies https://laravel.com/docs/9.x/authorization#writing-gates
         if(!$note->user->is(Auth::user())) {
            return abort(403);
        }

        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        if(!$note->user->is(Auth::user())) {
            return abort(403);
        }

        return view('notes.edit')->with('note', $note);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        if(!$note->user->is(Auth::user())) {
            return abort(403);
        }

        
        $request->validate([
                'title' => 'required|max:120',
                'text' => 'required'
        ]);

        $note->update([
            'title' => $request->title,
            'text' => $request->text

        ]);

        return to_route('notes.show', $note)->with('success', 'Note updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if(!$note->user->is(Auth::user())) {
            return abort(403);
        }


        $note->delete();

        return to_route('notes.index')->with('success', 'Note moved to trash');
        
    }
}
