<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            return response()->json([
                'message' => 'Logout first.'
            ], 403);
        }

        return $next($request);
    }
}
