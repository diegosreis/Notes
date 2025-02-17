<?php

use App\Http\Controllers\NotebookController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrashedNoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::resource('notes', NoteController::class)->middleware('auth');
Route::resource('notebooks', NotebookController::class)->middleware('auth');

Route::prefix('/trashed')->name('trashed.')->middleware('auth')->group(function () {
   route::get('/', [TrashedNoteController::class, 'index'])->name('index');
    route::get('/{note}', [TrashedNoteController::class, 'show'])->withTrashed()->name('show');
    route::put('/{note}', [TrashedNoteController::class, 'update'])->withTrashed()->name('update');
    route::delete('/{note}', [TrashedNoteController::class, 'destroy'])->withTrashed()->name('destroy');
});
