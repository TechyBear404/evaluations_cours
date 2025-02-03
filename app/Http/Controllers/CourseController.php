<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Form;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('year')->get();
        $years = Year::all();
        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'years' => $years
        ]);
    }
    public function show($id)
    {

        $course = Course::find($id)->load('teacher', 'form', 'students');
        $teachers = Teacher::all();
        $forms = Form::all();

        return Inertia::render('Courses/Show', [
            'course' => $course,
            'teachers' => $teachers,
            'forms' => $forms
        ]);
    }

    public function create()
    {
        return Inertia::render('Courses/Create', [
            'teachers' => Teacher::all(),
            'forms' => Form::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required',
            'form_id' => 'required',
            'year' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'emails' => 'nullable'
        ]);

        $year = Year::firstOrCreate(['year' => $request->input('year')]);
        $request->merge(['year_id' => $year->id]);

        $course = Course::create($request->only('name', 'teacher_id', 'form_id', 'year_id', 'start_date', 'end_date'));

        // Traiter les emails
        $emails = array_filter(array_map('trim', explode("\n", $request->input('emails'))));
        $studentIds = [];

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Trouver ou créer l'étudiant
                $student = Student::firstOrCreate(['email' => $email]);
                $studentIds[] = $student->id;
            }
        }

        // Lier les étudiants au cours
        if (!empty($studentIds)) {
            $course->students()->sync($studentIds);
        }

        return redirect()->route('courses.Index')->with('success', 'Le cours a été créé avec succès.');
    }

    public function update(String $id, Request $request)
    {
        $course = Course::find($id);


        $request->validate([
            'teacher_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'form_id' => 'required',
            "students" => "nullable"
        ]);

        $course->update($request->only('teacher_id', 'start_date', 'end_date', 'form_id'));
        $course_name = $course->name;

        // Traiter la liste des emails des étudiants
        $emails = array_filter(array_map('trim', explode("\n", $request->input('students'))));
        $studentIds = [];

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Trouver ou créer l'étudiant
                $student = Student::firstOrCreate(['email' => $email]);
                $studentIds[] = $student->id;
            }
        }
        // Mettre à jour les inscriptions : supprimer les anciennes et ajouter les nouvelles
        $course->students()->sync($studentIds);
        return redirect()->route('courses.Index')->with('success', "Le cours $course_name a été mis à jour.");
    }
}
