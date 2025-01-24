<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Form;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return Inertia::render('Courses/Index', [
            'courses' => $courses
        ]);
    }
    public function show($id)
    {

        $course = Course::find($id);
        return Inertia::render('Courses/Show', [
            'course' => $course
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
            'form_id' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'emails' => 'nullable'
        ]);

        // Créer le cours
        $course = Course::create($request->all());

        // Traiter les emails
        $emails = array_filter(array_map('trim', explode("\n", $request->input('emails'))));
        $studentIds = [];

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $student = Student::firstOrCreate(['email' => $email]);
                $studentIds[] = $student->id;
            }
        }

        // Lier les étudiants au cours en utilisant la table inscriptions
        if (!empty($studentIds)) {
            foreach ($studentIds as $studentId) {
                Inscription::create([
                    'course_id' => $course->id,
                    'student_id' => $studentId
                ]);
            }
        }

        return redirect()->route('courses.Index');
    }
}
