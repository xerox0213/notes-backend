<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->folders()->get();
    }
}
