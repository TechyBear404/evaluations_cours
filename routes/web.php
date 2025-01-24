<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.Index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.Create');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.Show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.Store');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.Index');
});
