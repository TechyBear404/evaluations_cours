<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Form;
use App\Models\Inscription;
use App\Models\Response;
use App\Models\Survey;
use App\Notifications\TeacherNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with(['course'])->get();

        // Get inscriptions count for each course
        $surveys = $surveys->map(function ($survey) {
            $survey->students_count = Inscription::where('course_id', $survey->course_id)->count();
            $survey->responses_count = Inscription::where('course_id', $survey->course_id)->where('survey_isfilled', true)->count();
            return $survey;
        });

        return Inertia::render('Survey/Index', [
            'surveys' => $surveys
        ]);
    }

    public function showDetails($id)
    {
        $survey = Survey::findOrFail($id)->load('course', 'responses', 'responses.option', 'responses.question');
        $survey->students_count = Inscription::where('course_id', $survey->course_id)->count();
        $survey->responses_count = Inscription::where('course_id', $survey->course_id)->where('survey_isfilled', true)->count();

        return Inertia::render('Survey/ShowDetails', [
            'survey' => $survey
        ]);
    }

    public function show($token)
    {
        $inscription = Inscription::where('token', $token)->first();

        if (!$inscription) {
            return Inertia::render('Notfound');
        }
        if ($inscription->survey_isfilled) {
            return redirect()->route('survey.hasResponded');
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
        $survey = Survey::where('course_id', $inscription->course_id)->first();

        if ($inscription->survey_isfilled) {
            return redirect()->route('survey.Thanks');
        }

        $reponses = $request->input('answers');

        foreach ($reponses as $response) {

            if ($response['type'] === "checkbox") {
                if (is_array($response['content'])) {
                    $response['content'] = implode(',', $response['content']);
                }
            }

            if ($response['type'] === "table_radio") {
                foreach ($response['content'] as $key => $value) {
                    Response::create([
                        'survey_id' => $survey->id,
                        'student_id' => $inscription->student_id,
                        'question_id' => $response['question_id'],
                        'option_id' => $value['option_id'],
                        'content' => $value['response'],

                    ]);
                }
            } else {
                Response::create([
                    'survey_id' => $survey->id,
                    'student_id' => $inscription->student_id,
                    'question_id' => $response['question_id'],
                    'content' => $response['content'],
                    'option_id' => null,
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

    public function hasResponded()
    {
        return Inertia::render('Survey/HasResponded');
    }

    public function sendExportedFile(Request $request, $id)
    {
        $survey = Survey::findOrFail($id)->load('course');
        $format = $request->input('format');
        $fileContent = $request->input('fileContent');
        $fileName = "evaluation_{$survey->course->name}.{$format}";

        if (empty($fileContent)) {
            return response()->json(['error' => 'Aucun fichier n\'a été fourni'], 422);
        }

        try {
            // Decode base64 content
            $decodedContent = base64_decode($fileContent, true);

            if ($decodedContent === false) {
                return response()->json(['error' => 'Contenu du fichier invalide'], 422);
            }

            $teacher = $survey->course->teacher;
            $message = "Vous trouverez ci-joint le fichier exporté pour le cours de " . $survey->course->name;
            $teacher->notify(new TeacherNotification($message, $decodedContent, $fileName));

            return redirect()->back()->with('success', 'Fichier envoyé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'envoi du fichier');
        }
    }
}
