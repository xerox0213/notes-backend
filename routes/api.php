<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
