<?php

namespace App\Http\Controllers;

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
}
