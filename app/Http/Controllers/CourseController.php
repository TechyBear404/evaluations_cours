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
use PhpParser\Node\Stmt\TryCatch;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['year', 'students', 'survey'])->get();
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'emails' => 'nullable|array',
            'emails.*' => 'email:rfc,dns'
        ], [
            'name.required' => 'Le nom du cours est requis',
            'teacher_id.required' => 'Le professeur est requis',
            'form_id.required' => 'Le formulaire est requis',
            'start_date.required' => 'La date de début est requise',
            'start_date.date' => 'La date de début doit être une date valide',
            'end_date.required' => 'La date de fin est requise',
            'end_date.date' => 'La date de fin doit être une date valide',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début',
            'emails.*.email' => 'L\'email :input n\'est pas valide'
        ]);


        try {
            $year = Year::firstOrCreate(['year' => $request->input('year')]);
            $request->merge(['year_id' => $year->id]);

            $course = Course::create($request->only('name', 'teacher_id', 'form_id', 'year_id', 'start_date', 'end_date'));

            $studentIds = [];
            if ($request->input('emails')) {
                foreach ($request->input('emails', []) as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $student = Student::firstOrCreate(['email' => $email]);
                        $studentIds[] = $student->id;
                    }
                }

                if (!empty($studentIds)) {
                    $studentData = [];
                    foreach ($studentIds as $studentId) {
                        $studentData[$studentId] = ['token' => Str::random(32)];
                    }
                    $course->students()->sync($studentData);
                }
            }

            return redirect()->route('courses.index')->with('success', 'Le cours a été créé avec succès.');
        } catch (\Throwable $th) {
            return redirect()->route('courses.index')->with('error', 'Erreur lors de la création du cours.');
        }
    }

    public function update(String $id, Request $request)
    {
        try {
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
        } catch (\Throwable $th) {
            return redirect()->route('courses.index')->with('error', 'Erreur lors de la mise à jour du cours.');
        }
    }

    public function destroy(string $id, Request $request)
    {
        try {
            $course = Course::find($id);
            $course_name = $course->name;
            $course->delete();
            return redirect()->route('courses.index')->with('success', "Le cours $course_name a été supprimé.");
        } catch (\Throwable $th) {
            return redirect()->route('courses.index')->with('error', 'Erreur lors de la suppression du cours.');
        }
    }
}
