<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class TrashedNoteController extends Controller
{
    public function index()
    {
//        $notes = Auth::user()->notes()->onlyTrashed()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->onlyTrashed()->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);

    }

    public function show(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return to_route('notes.index');
            //or abort(403)
        }
        return view('notes.show')->with('note', $note);
    }

    public function update(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return to_route('notes.index');
        }
        $note->restore();
        return to_route('notes.show', $note)
            ->with('success', 'Restored!');
    }

    public function destroy(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return to_route('notes.index');
        }
        $note->forceDelete();
        return to_route('trashed.index')
            ->with('success', 'Deleted forever!');
    }
}
