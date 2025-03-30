<?php

namespace App\Http\Controllers;

use App\Http\Resources\PartialNoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SoftDeletedNoteController extends Controller
{
    public function index(Request $request)
    {
        $softDeletedNotes = Note::onlyTrashed()->where('user_id', '=', $request->user()->id)->get();
        return response()->json(PartialNoteResource::collection($softDeletedNotes));
    }

    public function destroy(Note $note)
    {
        Gate::authorize('forceDelete', $note);
        $note->forceDelete();
        return response()->noContent();
    }
}
