<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inscription>
 */
class InscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Stocker les combinaisons déjà utilisées
        static $existingPairs = [];

        do {
            $courseId = Course::pluck('id')->random();
            $studentId = Student::pluck('id')->random();
            $pair = "$courseId-$studentId";
        } while (in_array($pair, $existingPairs)); // Répéter si la paire existe déjà

        // Ajouter la paire à la liste des combinaisons utilisées
        $existingPairs[] = $pair;

        return [
            'course_id' => $courseId,
            'student_id' => $studentId,
            'token' => fake()->unique()->sha1(),
        ];
    }
}
