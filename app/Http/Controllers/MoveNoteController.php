<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveNoteRequest;
use App\Http\Resources\PartialNoteResource;
use App\Models\Note;
use Illuminate\Support\Facades\Gate;

class MoveNoteController extends Controller
{
    public function update(Note $note, MoveNoteRequest $request)
    {
        Gate::authorize('update', $note);
        if ($note->trashed()) $note->restore();
        $newFolder = $request->validated();
        $note->update($newFolder);
        return response()->json(new PartialNoteResource($note));
    }
}
