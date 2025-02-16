<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Form;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

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

    public function store(CourseRequest $request)
    {
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

    public function update(String $id, CourseRequest $request)
    {
        try {
            $year = Year::firstOrCreate(['year' => $request->input('year')]);
            $request->merge(['year_id' => $year->id]);
            $course = Course::find($id);
            $course->update($request->only('name', 'teacher_id', 'form_id', 'year_id', 'start_date', 'end_date'));


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
            } else {
                $course->students()->detach();
            }

            return redirect()->route('courses.index')->with('success', 'Le cours a été modifié avec succès.');
        } catch (\Throwable $th) {
            return redirect()->route('courses.index')->with('error', 'Erreur lors de la modification du cours.');
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
            // dd($th);
            return redirect()->route('courses.index')->with('error', 'Erreur lors de la suppression du cours.');
        }
    }
}