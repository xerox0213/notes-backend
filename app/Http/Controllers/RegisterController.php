<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request) {
        $credentials = $request->validated();
        $credentials['password'] = bcrypt($credentials['password']);
        User::create($credentials);
        return response()->noContent();
    }
}
