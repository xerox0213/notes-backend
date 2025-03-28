<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\IndexNoteResource;
use App\Http\Resources\ShowNoteResource;
use App\Models\Folder;
use App\Models\Note;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function index(Folder $folder)
    {
        Gate::authorize('view', $folder);
        $notes = $folder->notes()->get();
        return response()->json(IndexNoteResource::collection($notes));
    }

    public function show(Note $note)
    {
        Gate::authorize('view', $note);
        return response()->json(new ShowNoteResource($note));
    }

    public function store(Folder $folder, StoreNoteRequest $request)
    {
        Gate::authorize('view', $folder);
        $noteData = $request->validated();
        $noteData['folder_id'] = $folder->id;
        $note = Note::create($noteData);
        return response()->json(new IndexNoteResource($note), 201);
    }

    public function update(Note $note, UpdateNoteRequest $request)
    {
        Gate::authorize('update', $note);
        $noteData = $request->validated();
        $note->update($noteData);
        return response()->json(new ShowNoteResource($note));
    }

    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);
        $note->delete();
        return response()->noContent();
    }
}
