<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->folders()->get();
    }

    public function destroy(Folder $folder)
    {
        Gate::authorize('delete', $folder);
        $folder->notes()->delete();
        $folder->delete();
        return response()->noContent();
    }

    public function update(Folder $folder, UpdateFolderRequest $request)
    {
        Gate::authorize('update', $folder);
        $folder->name = $request->safe()->input('name');
        $folder->save();
        return response()->noContent();
    }
}
