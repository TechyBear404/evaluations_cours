<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Form;
use App\Models\Inscription;
use App\Models\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function show($token)
    {
        $inscription = Inscription::where('token', $token)->first();

        $course = $inscription->course()->first();

        $form = $course->form()->with(['questions.component', 'questions.options'])->first();


        return Inertia::render('Survey/Show', [
            'token' => $token,
            'form' => $form,
            'course' => $course,
        ]);
    }

    public function submitForm(Request $request)
    {
        dd($request->all());
        // dd($request->input('responses'));
        $reponses = $request->input('answers');
        $course_id = $request->input('course_id');

        foreach ($reponses as $response) {

            if (is_array($response['content'])) {
                $response['content'] = implode(',', $response['content']);
            }

            Response::create([
                'question_id' => $response['question_id'],
                'course_id' => $course_id,
                'content' => $response['content'],
            ]);
        }

        return redirect()->route('survey.Thanks');
    }

    public function thanks()
    {
        return Inertia::render('Survey/Thanks');
    }
}
