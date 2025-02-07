<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Form;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['year', 'students'])->get();
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
        try {
            $validated = $request->validate([
                'name' => 'required',
                'teacher_id' => 'required',
                'form_id' => 'required',
                'year' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'emails' => 'nullable',
                'emails.*' => 'email:rfc,dns'
            ], [
                'emails.*.email' => 'L\'email :input n\'est pas valide'
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            // dd($errors);
            return redirect()->back()->withErrors($errors);
        }

        $year = Year::firstOrCreate(['year' => $request->input('year')]);
        $request->merge(['year_id' => $year->id]);

        $course = Course::create($request->only('name', 'teacher_id', 'form_id', 'year_id', 'start_date', 'end_date'));

        // Traiter les emails
        // $emails = array_filter(array_map('trim', explode("\n", $request->input('emails'))));
        $studentIds = [];

        foreach ($request->input('emails') as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Trouver ou créer l'étudiant
                $student = Student::firstOrCreate(['email' => $email]);
                $studentIds[] = $student->id;
            }
        }

        // Lier les étudiants au cours
        if (!empty($studentIds)) {
            // $course->students()->sync($studentIds);
            // $course->students()->syncWithPivotValues($studentIds, ['token' => Str::random(32)]);
            // foreach ($studentIds as $studentId) {
            //     $course->students()->syncWithoutDetaching([
            //         $studentId => ['token' => Str::random(32)]
            //     ]);
            // }
            $studentData = [];
            foreach ($studentIds as $studentId) {
                $studentData[$studentId] = ['token' => Str::random(32)];
            }
            $course->students()->sync($studentData);
        }

        return redirect()->route('courses.index')->with('success', 'Le cours a été créé avec succès.');
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
        $studentData = [];
        foreach ($studentIds as $studentId) {
            $studentData[$studentId] = ['token' => Str::random(32)];
        }

        // Sync avec valeurs pivot → supprime les étudiants non présents dans $studentIds
        $course->students()->sync($studentData);
        return redirect()->route('courses.index')->with('success', "Le cours $course_name a été mis à jour.");
    }

    public function destroy(Request $request)
    {
        $course = Course::find($request->input('id'));
        $course_name = $course->name;
        $course->delete();
        return redirect()->route('courses.index')->with('success', "Le cours $course_name a été supprimé.");
    }
}
