<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Form;
use App\Models\Course;
use App\Models\Survey;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormController extends Controller
{
    function index()
    {
        $forms = Form::all();

        return Inertia::render('Form/Index', [
            'forms' => $forms
        ]);
    }

    function create()
    {
        $components = Component::all();
        return Inertia::render('Form/Create', [
            'components' => $components
        ]);
    }

    public function store(Request $request)
    {
        try {
            $form = Form::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_locked' => false
            ]);

            $this->saveFormComponents($form, $request->components);

            return redirect()->route('form.index')->with('success', 'Formulaire créé avec succès');
        } catch (\Throwable $th) {
            return redirect()->route('form.index')->with('error', 'Erreur lors de la création du formulaire');
        }
    }

    function show(Form $form)
    {
        $form->load(['questions.options', 'questions.component']);

        // Restructure les données comme pour la fonction edit
        $formData = [
            'id' => $form->id,
            'name' => $form->name,
            'description' => $form->description,
            'components' => $form->questions->map(function ($question) {
                $componentData = [
                    'id' => $question->id,
                    'type' => $question->component->type,
                    'question' => $question->label,
                    'name' => $question->component->name,
                    'sourceId' => $question->component_id
                ];

                // Gestion des options en fonction du type
                if ($question->component->type === 'table_radio') {
                    $componentData['tableData'] = [
                        'columns' => $question->options->where('type', 'column')->pluck('name')->values(),
                        'rows' => $question->options->where('type', 'row')->pluck('name')->values(),
                    ];
                } elseif (in_array($question->component->type, ['radio', 'checkbox'])) {
                    $componentData['options'] = $question->options->pluck('name')->values();
                }

                return $componentData;
            })->values()
        ];

        $components = Component::all();

        return Inertia::render('Form/Show', [
            'form' => $formData,
            'components' => $components
        ]);
    }

    public function update(Request $request, Form $form)
    {
        try {
            $form->update(['name' => $request->input('name')]);

            $form->questions()->delete();

            $this->saveFormComponents($form, $request->components);

            return redirect()->route('form.index')->with('success', 'Formulaire modifié avec succès');
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()->with('error', 'Le formulaire n\'a pas pu être modifié car il est attaché à une enquête');
            }
        }
    }

    public function duplicate(Form $form)
    {
        $newForm = $form->replicate();
        $newForm->name = $form->name . ' (Copie)';
        $newForm->save();

        $form->questions->each(function ($question) use ($newForm) {
            $newQuestion = $question->replicate();
            $newQuestion->form_id = $newForm->id;
            $newQuestion->save();

            $question->options->each(function ($option) use ($newQuestion) {
                $newOption = $option->replicate();
                $newOption->question_id = $newQuestion->id;
                $newOption->save();
            });
        });

        return redirect()->route('form.index')->with('success', 'Formulaire dupliqué avec succès');
    }

    public function destroy(Form $form)
    {
        try {
            $form->delete();
            return redirect()->back()->with('success', 'Formulaire supprimé avec succès');
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()->with('error', 'Le formulaire n\'a pas pu être supprimé car il est attaché à une enquête');
            }
        }
    }

    private function saveFormComponents($form, $components)
    {
        foreach ($components as $index => $component) {
            $question = $form->questions()->create([
                'label' => $component['question'],
                'component_id' => $component['sourceId'],
                'order' => $index,
                'form_id' => $form->id  // Ajout explicite du form_id
            ]);

            // Gérer les options standards (radio, checkbox)
            if (!empty($component['options'])) {
                $question->options()->createMany(
                    collect($component['options'])
                        ->filter()
                        ->map(fn($option, $index) => [
                            'name' => $option,
                            'question_id' => $question->id,  // Ajout explicite du question_id
                            'type' => 'default',  // Ajout du type par défaut
                            'order' => $index  // Ajout du champ order requis
                        ])
                        ->toArray()
                );
            }

            // Gérer les options de table
            if (!empty($component['tableData'])) {
                // Colonnes
                $question->options()->createMany(
                    collect($component['tableData']['columns'])
                        ->filter()
                        ->map(fn($col, $index) => [
                            'name' => $col,
                            'type' => 'column',
                            'question_id' => $question->id,
                            'order' => $index
                        ])
                        ->toArray()
                );

                // Lignes
                $question->options()->createMany(
                    collect($component['tableData']['rows'])
                        ->filter()
                        ->map(fn($row, $index) => [
                            'name' => $row,
                            'type' => 'row',
                            'question_id' => $question->id,
                            'order' => $index
                        ])
                        ->toArray()
                );
            }
        }
    }



    function edit(Form $form)
    {
        $components = Component::all();
        $form->load(['questions' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'questions.options' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'questions.component']);

        $formData = [
            'id' => $form->id,
            'name' => $form->name,
            'components' => $form->questions->map(function ($question) {
                $componentData = [
                    'id' => $question->id,
                    'tempId' => $question->component_id, // Ajout de l'ID du composant original
                    'type' => $question->component->type,
                    'question' => $question->label,
                    'name' => $question->component->name, // Ajout du nom du composant
                ];

                // Vérifier si les options existent
                if ($question->options && $question->component->type === 'table_radio') {
                    $componentData['tableData'] = [
                        'columns' => $question->options->where('type', 'column')->pluck('name')->values() ?? collect([]),
                        'rows' => $question->options->where('type', 'row')->pluck('name')->values() ?? collect([]),
                    ];
                } elseif ($question->options && in_array($question->component->type, ['radio', 'checkbox'])) {
                    $componentData['options'] = $question->options->pluck('name')->values() ?? collect([]);
                }

                // Initialiser les tableaux vides si nécessaire
                if ($question->component->type === 'table_radio' && !isset($componentData['tableData'])) {
                    $componentData['tableData'] = [
                        'columns' => [],
                        'rows' => []
                    ];
                } elseif (in_array($question->component->type, ['radio', 'checkbox']) && !isset($componentData['options'])) {
                    $componentData['options'] = [];
                }

                return $componentData;
            })->values()
        ];

        return Inertia::render('Form/Edit', [
            'form' => $formData,
            'components' => $components
        ]);
    }
    public function sendForm(string $courseId)
    {
        try {
            $course = Course::find($courseId);
            $students = $course->students;

            foreach ($students as $student) {
                $link = route('survey.show', ['token' => $student->pivot->token]);
                $message = "Vous trouverez ci joint le formulaire à remplir pour le cours de " . $course->name;
                $student->notify(new UserNotification($message, $link));
            }

            if (!Survey::where('course_id', $course->id)->where('is_sent', true)->exists()) {
                Survey::create([
                    'course_id' => $course->id,
                    'is_sent' => true,
                    'start_date' => now(),
                    'end_date' => now()->addDays(15)
                ]);
            }

            $course->is_sent = true;
            $course->save();

            return redirect()->back()->with('success', 'Formulaire envoyé avec succès');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi du formulaire');
        }
    }
}
