<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/education', function () {
    return view('education');
})->name('education');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('projects.index');
    })->name('dashboard');
    Route::get('/post/profile-saya', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/post/projects', [ProjectController::class, 'index'])->name('post.projects');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/trash', [ProjectController::class, 'trash'])->name('projects.trash');
    Route::patch('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('/projects/{project}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.forceDelete');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::match(['put', 'patch'], '/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

require __DIR__.'/auth.php';
