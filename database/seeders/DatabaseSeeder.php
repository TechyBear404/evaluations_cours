<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\Course;
use App\Models\Form;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        Student::factory(10)->create();
        Teacher::factory(10)->create();

        $courses = [
            "PHP",
            "JavaScript",
            "Design",
            "Projet web Dynamique",
            "Projet web Statique",
        ];
        foreach ($courses as $course) {
            Course::factory()->create([
                'name' => $course,
            ]);
        }
        Inscription::factory(10)->create();

        Component::create([
            'name' => 'champs de texte court',
            'type' => 'input',
        ]);
        Component::create([
            'name' => 'champs de texte long',
            'type' => 'textarea',
        ]);
        Component::create([
            'name' => 'choix unique',
            'type' => 'radio',
        ]);
        Component::create([
            'name' => 'choix multiple',
            'type' => 'checkbox',
        ]);
        Component::create([
            'name' => 'tableau de choix',
            'type' => 'table_radio',
        ]);

        Form::create(['name' => 'Formulaire 1']);
        Form::create(['name' => 'Formulaire 2']);
    }
}
