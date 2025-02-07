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
        // redireger si l'enquete a déjà été soumise


        if (!$inscription) {
            //redirige vers la page not found
            return Inertia::render('Notfound');
        }

        if ($inscription->survey_isfilled) {
            return redirect()->route('survey.Thanks');
        }



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
        $token = $request->route('token');
        $inscription = Inscription::where('token', $token)->first();

        // redireger si l'enquete a déjà été soumise
        if ($inscription->survey_isfilled) {
            return redirect()->route('survey.Thanks');
        }

        $reponses = $request->input('answers');
        // $course_id = $request->input('course_id');

        foreach ($reponses as $response) {

            if ($response['type'] === "checkbox") {
                if (is_array($response['content'])) {
                    $response['content'] = implode(',', $response['content']);
                }
            }

            if ($response['type'] === "table_radio") {
                foreach ($response['content'] as $key => $value) {
                    Response::create([
                        'question_id' => $response['question_id'],
                        // 'course_id' => $course_id,
                        'option_id' => $value['option_id'],
                        'content' => $value['response'],
                        'inscription_id' => $inscription->id,

                    ]);
                }
            } else {
                Response::create([
                    'question_id' => $response['question_id'],
                    // 'course_id' => $course_id,
                    'content' => $response['content'],
                    'option_id' => null,
                    'inscription_id' => $inscription->id,
                ]);
            }
        }
        $inscription->survey_isfilled = true;
        $inscription->save();

        return redirect()->route('survey.Thanks');
    }

    public function thanks()
    {
        return Inertia::render('Survey/Thanks');
    }
}