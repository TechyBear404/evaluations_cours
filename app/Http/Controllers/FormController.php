<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Form;
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
        $form = Form::create([
            'name' => $request->title,
            'is_locked' => false
        ]);

        foreach ($request->components as $index => $componentData) {
            $question = $form->questions()->create([
                'label' => $componentData['question'],
                'component_id' => $componentData['id'],
                'order' => $index
            ]);

            if (isset($componentData['options'])) {
                foreach ($componentData['options'] as $option) {
                    $question->options()->create([
                        'name' => $option
                    ]);
                }
            }

            if (isset($componentData['tableData'])) {
                foreach ($componentData['tableData']['columns'] as $column) {
                    $question->options()->create([
                        'name' => $column,
                        'type' => 'column'
                    ]);
                }

                foreach ($componentData['tableData']['rows'] as $row) {
                    $question->options()->create([
                        'name' => $row,
                        'type' => 'row'
                    ]);
                }
            }
        }

        return redirect()->route('form.index');
    }

    function show(Form $form)
    {
        return Inertia::render('Form/Show', [
            'form' => $form
        ]);
    }

    function edit(Form $form)
    {
        $components = Component::all();
        $form->load(['questions.options', 'questions.component']);

        // Restructure the data for the frontend
        $formData = [
            'id' => $form->id,
            'title' => $form->name,
            'components' => $form->questions->map(function ($question) {
                $componentData = [
                    'id' => $question->id,
                    'type' => $question->component->type,
                    'question' => $question->label,
                ];

                if ($question->component->type === 'table_radio') {
                    $componentData['tableData'] = [
                        'columns' => $question->options->where('type', 'column')->pluck('name'),
                        'rows' => $question->options->where('type', 'row')->pluck('name'),
                    ];
                } else if (in_array($question->component->type, ['radio', 'checkbox'])) {
                    $componentData['options'] = $question->options->pluck('name');
                }

                return $componentData;
            })
        ];

        return Inertia::render('Form/Edit', [
            'form' => $formData,
            'components' => $components
        ]);
    }
}
