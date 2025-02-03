<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        return [
            'lastname' => fake()->unique()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'firstname' => fake()->firstName(),
        ];
    }
}
