<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{id}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
