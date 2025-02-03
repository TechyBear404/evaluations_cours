<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\Year;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
            'teacher_id' => Teacher::pluck('id')->random(),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+1 year'),
            'year_id' => Year::pluck('id')->random(),
        ];
    }
}
