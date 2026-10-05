<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/education', function () {
    return view('education');
})->name('education');

Route::get('/projects/trash', [ProjectController::class, 'trash'])->name('projects.trash');
Route::patch('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
Route::delete('/projects/{project}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.forceDelete');
Route::resource('projects', ProjectController::class);