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
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.Index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.Create');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.Show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.Store');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.Update');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.Index');
    Route::get('/forms', [FormController::class, 'index'])->name('forms.Index');

    Route::get('/test-email', function () {
        $user = User::first(); // Récupère un utilisateur
        $message = "Ceci est un email de test.";

        $user->notify(new UserNotification($message));

        return "Email envoyé avec succès !";
    });

    // `/courses/${courseId}/send-form`
    Route::get('/courses/{courseId}/send-form', [FormController::class, 'sendForm'])->name('courses.SendForm');
});

Route::get('/forms/{id}', [FormController::class, 'show'])->name('forms.Show');
