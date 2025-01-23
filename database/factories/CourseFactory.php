<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
            'teacher_id' => Teacher::pluck('id')->random(),
            'end_date' => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }
}