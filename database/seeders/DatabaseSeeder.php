<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. PRIMEIRO: Rodamos as dependências base (Usuários fixos, Admins)
        $this->call([
            UserSeeder::class
        ]);

        // 2. SEGUNDO: Criamos os Cursos e suas respectivas Disciplinas
        Course::factory(10)->create()->each(function ($course) {
            Subject::factory(3)->create([
                'course_id' => $course->id
            ]);
        });

        // 3. TERCEIRO (Bônus de Arquiteto): População de Matrículas (Enrollments)
        // Criamos 15 estudantes (ou usamos os existentes) e os matriculamos em cursos aleatórios
        User::factory(15)->create(['role' => 'student'])->each(function ($student) {

            // Sorteia de 1 a 3 cursos para este aluno se matricular
            $coursesToEnroll = Course::inRandomOrder()->take(rand(1, 3))->pluck('id');

            foreach ($coursesToEnroll as $courseId) {
                Enrollment::factory()->create([
                    'user_id'   => $student->id,
                    'course_id' => $courseId,
                ]);
            }
        });
    }
}
