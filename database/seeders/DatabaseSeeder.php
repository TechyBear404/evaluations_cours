<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\Course;
use App\Models\Form;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Year;
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
        Form::create(['name' => 'Formulaire 1']);
        Form::create(['name' => 'Formulaire 2']);

        User::factory()->create([
            'name' => 'hadrien',
            'email' => 'hadrien.janssens7@gmail.com',
        ]);
        User::factory()->create([
            'name' => 'sebastien',
            'email' => 'ector.seb@gmail.com',
            'password' => bcrypt('password1234'),
        ]);
        Student::factory(10)->create();
        Teacher::factory(10)->create();

        Year::create(['year' => '2020']);
        Year::create(['year' => '2021']);
        Year::create(['year' => '2022']);
        Year::create(['year' => '2023']);
        Year::create(['year' => '2024']);

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
                'form_id' => Form::all()->random()->id,
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
    }
}
