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
        try {
            $request->validate([
                'lastname' => 'required',
                'email' => 'required|email',
                'firstname' => 'required',
            ]);

            Teacher::create($request->all());

            return redirect()->route('teachers.index')->with('success', 'Professeur ajouté avec succès');
        } catch (\Throwable $th) {
            return redirect()->route('teachers.index')->with('error', 'Erreur lors de l\'ajout du professeur');
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'lastname' => 'required',
                'email' => 'required|email',
                'firstname' => 'required',
            ]);

            $teacher = Teacher::find($request->id);
            $teacher->update($request->all());

            return redirect()->route('teachers.index')->with('success', 'Professeur modifié avec succès');
        } catch (\Throwable $th) {
            return redirect()->route('teachers.index')->with('error', 'Erreur lors de la modification du professeur');
        }
    }

    public function delete(Request $request)
    {

        try {
            Teacher::destroy($request->id);
            return redirect()->route('teachers.index')->with('success', 'Professeur supprimé avec succès');
        } catch (\Throwable $th) {
            return redirect()->route('teachers.index')->with('error', 'Erreur lors de la suppression du professeur');
        }
    }
}
