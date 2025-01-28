<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Form;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormController extends Controller
{
    public function index()
    {
        return Inertia::render('Forms/Index', [
            'forms' => Form::all()
        ]);
    }
    public function show(string $id)
    {
        $form = Form::find($id);
        return Inertia::render('Forms/Show', [
            'form' => $form
        ]);
    }
    public function sendForm(string $courseId)
    {

        $course = Course::find($courseId);
        $students = $course->students;
        $link = route('forms.Show', ['id' => $course->form_id]);

        foreach ($students as $student) {
            $message = "Vous trouverez ci joint le formulaire à remplir pour le cours de " . $course->name;

            $student->notify(new UserNotification($message, $link));
        }
        return redirect()->back();
    }
}
