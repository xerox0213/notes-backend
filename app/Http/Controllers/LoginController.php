<?php

namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return response()->noContent();
        }

        return response()->json(['message' => 'Invalid email or password'], 401);
    }
}
