<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\PartialNoteResource;
use App\Http\Resources\FullNoteResource;
use App\Models\Folder;
use App\Models\Note;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function index(Folder $folder)
    {
        Gate::authorize('view', $folder);
        $notes = $folder->notes()->get();
        return response()->json(PartialNoteResource::collection($notes));
    }

    public function show(Note $note)
    {
        Gate::authorize('view', $note);
        return response()->json(new FullNoteResource($note));
    }

    public function store(Folder $folder, StoreNoteRequest $request)
    {
        Gate::authorize('view', $folder);
        $noteData = $request->validated();
        $noteData['folder_id'] = $folder->id;
        $noteData['user_id'] = $request->user()->id;
        $note = Note::create($noteData);
        return response()->json(new PartialNoteResource($note), 201);
    }

    public function update(Note $note, UpdateNoteRequest $request)
    {
        Gate::authorize('update', $note);
        $noteData = $request->validated();
        $note->update($noteData);
        return response()->json(new FullNoteResource($note));
    }

    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);
        $note->delete();
        return response()->noContent();
    }
}
