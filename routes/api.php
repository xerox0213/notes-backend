<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\MoveNoteController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SoftDeletedNoteController;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::apiResource("folders", FolderController::class)->only('index', 'destroy', 'update');
    Route::get('/notes/deleted', [SoftDeletedNoteController::class, 'index'])->name('notes-deleted.index');
    Route::patch('/notes/{note}/move', [MoveNoteController::class, 'update'])->name('notes.move')->withTrashed();
    Route::delete('/notes/deleted/{note}', [SoftDeletedNoteController::class, 'destroy'])->name('notes-deleted.destroy')->withTrashed();
    Route::apiResource('folders.notes', NoteController::class)->shallow();
});
