<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\SendEmailFormController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\TeacherController;
use App\Models\User;
use App\Notifications\UserNotification;
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

    Route::get('/form', [FormController::class, 'index'])->name('form.index');
    Route::get('/form/create', [FormController::class, 'create'])->name('form.create');
    Route::post('/form/store', [FormController::class, 'store'])->name('form.store');
    Route::get('/form/{form}', [FormController::class, 'show'])->name('form.show');
    Route::put('/form/{form}', [FormController::class, 'update'])->name('form.update');
    Route::delete('/form/{form}', [FormController::class, 'destroy'])->name('form.destroy');
  
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.Index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.Create');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.Show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.Store');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.Update');
    Route::get('/courses/{courseId}/send-form', [FormController::class, 'sendForm'])->name('courses.SendForm');
  
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.Index');
    

});

Route::get('/forms/{id}', [FormController::class, 'show'])->name('forms.Show');
