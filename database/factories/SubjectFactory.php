<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * O nome do model correspondente a esta factory.
     */
    protected $model = Subject::class;

    /**
     * Definição do estado padrão.
     */
    public function definition(): array
    {
        return [
            'name'        => str(fake()->unique()->words(2, true))->title(),
            'description' => fake()->realText(150), 
            'course_id'   => Course::factory(),
            'user_id'     => User::factory()->create(['role' => 'teacher'])->id,
        ];
    }
}