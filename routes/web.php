<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\SendEmailFormController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\TeacherController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);


    return redirect()->route('courses.index');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');

    Route::get('/form', [FormController::class, 'index'])->name('form.index');
    Route::get('/form/create', [FormController::class, 'create'])->name('form.create');
    Route::post('/form/store', [FormController::class, 'store'])->name('form.store');
    Route::get('/form/{form}', [FormController::class, 'show'])->name('form.show');
    Route::put('/form/{form}', [FormController::class, 'update'])->name('form.update');
    Route::delete('/form/{form}', [FormController::class, 'destroy'])->name('form.destroy');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::post('/courses/{courseId}/send-form', [FormController::class, 'sendForm'])->name('courses.sendForm');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/teachers', [TeacherController::class, 'create'])->name('teachers.create');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update'); // Change to include {id} in the route
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
    Route::get('/survey/report/{id}', [SurveyController::class, 'showDetails'])->name('survey.details');
    Route::post('/survey/{id}/send-exported-file', [SurveyController::class, 'sendExportedFile'])->name('survey.sendExportedFile');
});

Route::get('/survey/thanks', [SurveyController::class, 'thanks'])->name('survey.thanks');
Route::get('/survey/hasresponded', [SurveyController::class, 'hasResponded'])->name('survey.hasResponded');
Route::get('/survey/{token}', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/{token}', [SurveyController::class, 'submitForm'])->name('survey.store');


//faire la route notFound
Route::get('/{any}', function () {
    return Inertia::render('Notfound');
})->where('any', '.*');
