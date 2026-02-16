<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    /**
     * O nome do model correspondente a esta factory.
     */
    protected $model = Enrollment::class;

    /**
     * Definição do estado padrão.
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pendente', 'cursando', 'concluido', 'cancelado']);

        return [
            // Garante que a fábrica gere um estudante válido
            'user_id'      => User::factory()->create(['role' => 'student'])->id,
            'course_id'    => Course::factory(),
            'status'       => $status,
            // Se o status for concluído, gera uma data no passado, senão, null
            'completed_at' => $status === 'concluido' ? fake()->dateTimeBetween('-6 months', 'now') : null,
        ];
    }
}
