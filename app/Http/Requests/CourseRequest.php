<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autoriser toutes les requêtes
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'teacher_id' => 'required',
            'form_id' => 'required',
            'year' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'emails' => 'nullable|array',
            'emails.*' => 'email:rfc,dns'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom du cours est requis',
            'teacher_id.required' => 'Le professeur est requis',
            'form_id.required' => 'Le formulaire est requis',
            'start_date.required' => 'La date de début est requise',
            'start_date.date' => 'La date de début doit être une date valide',
            'end_date.required' => 'La date de fin est requise',
            'end_date.date' => 'La date de fin doit être une date valide',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début',
            'emails.*.email' => 'L\'email :input n\'est pas valide'
        ];
    }
}
