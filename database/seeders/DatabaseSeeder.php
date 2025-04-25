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
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // admin login
        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => env('ADMIN_PASSWORD')
        ]);

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


        //If you want fake data, uncomment ALL this following lignes

        // Form::create(['name' => 'Form example 1']);
        // Form::create(['name' => 'Form example 2']);

        // Student::factory(1)->create([
        //     'email' => 'student@example.com',
        // ]);
        // Teacher::factory(1)->create([
        //     'email' => 'teacher@example.com',

        // ]);

        // Year::create(['year' => Carbon::now()->format('Y') - 1]);
        // Year::create(['year' => Carbon::now()->format('Y')]);

        // $courses = [
        //     "Course 1",
        //     "Course 2",

        // ];
        // foreach ($courses as $course) {
        //     Course::factory()->create([
        //         'name' => $course,
        //         'form_id' => Form::all()->random()->id,
        //     ]);
        // }

        // Inscription::factory(2)->create();
    }
}
