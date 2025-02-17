<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Notebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $notes = Auth::user()->notes()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notebooks = Notebook::where('user_id', Auth::id())->get();
        return view('notes.create')->with('notebooks', $notebooks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        Auth::user()->notes()->create([
            'notebook_id' => $request->notebook_id,
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'text' => $request->text
        ]);
        return to_route('notes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return to_route('notes.index');
            //or abort(403)
        }
        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return to_route('notes.index');
            //or abort(403)
        }
        $notebooks = Notebook::where('user_id', Auth::id())->get();

        return view('notes.edit')
            ->with('note', $note)
            ->with('notebooks', $notebooks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        if ($note->user_id != Auth::id()) {
            return to_route('notes.index');
        }

        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        $note->update([
            'title' => $request->title,
            'text' => $request->text,
            'notebook_id' => $request->notebook_id
        ]);
        $note->save();
        return to_route('notes.show', $note)
            ->with('success', 'Changes saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return to_route('notes.index');
        }
        $note->delete();
        return to_route('notes.index')
            ->with('success', 'Moved to trash!');
    }
}
