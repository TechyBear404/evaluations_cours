<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        return Inertia::render('Teachers/Index', [
            'teachers' => Teacher::all()
        ]);
    }
    public function create(Request $request)
    {

        $request->validate([
            'lastname' => 'required',
            'email' => 'required|email',
            'firstname' => 'required',
        ]);

        Teacher::create($request->all());

        return redirect()->route('teachers.Index');
    }
    public function delete(Request $request)
    {
        Teacher::destroy($request->id);
        return redirect()->route('teachers.Index');
    }
}