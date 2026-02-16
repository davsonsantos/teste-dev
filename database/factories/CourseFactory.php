<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * O nome do model correspondente a esta factory.
     */
    protected $model = Course::class;

    /**
     * Definição do estado padrão.
     */
    public function definition(): array
    {
        // Gera uma data de início entre o mês passado e o próximo mês
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');

        return [
            'name'        => str(fake()->unique()->words(3, true))->title(),
            'description' => fake()->paragraph(),
            'start_date'  => $startDate->format('Y-m-d'),
            'end_date'    => (clone $startDate)->modify('+' . fake()->numberBetween(15, 90) . ' days')->format('Y-m-d'),
        ];
    }
}