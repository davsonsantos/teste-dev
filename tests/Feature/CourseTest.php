<?php

use App\Models\Course;
use App\Models\User;
use function Pest\Laravel\{actingAs, get, post, put, delete, assertDatabaseHas, assertDatabaseMissing};

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    actingAs($this->admin);
});

it('can render the courses index', function () {
    get(route('course.index'))->assertOk();
});

it('can create a course with valid dates', function () {
    $courseData = [
        'name' => 'Curso de Pest PHP',
        'description' => 'Aprenda testes automatizados',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addDays(30)->format('Y-m-d'),
    ];

    post(route('course.store'), $courseData)->assertRedirect();

    assertDatabaseHas('courses', ['name' => 'Curso de Pest PHP']);
});

it('fails to create a course if end_date is before start_date', function () {
    $courseData = [
        'name' => 'Curso Inválido',
        'description' => 'Datas erradas',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->subDays(5)->format('Y-m-d'), // Data no passado
    ];

    post(route('course.store'), $courseData)->assertSessionHasErrors('end_date');
    assertDatabaseMissing('courses', ['name' => 'Curso Inválido']);
});

it('can update a course', function () {
    $course = Course::factory()->create();

    $updateData = [
        'name' => 'Curso Atualizado',
        'description' => 'Nova descrição',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addDays(10)->format('Y-m-d'),
    ];

    put(route('course.update', $course), $updateData)->assertRedirect();

    assertDatabaseHas('courses', ['name' => 'Curso Atualizado']);
});

it('can delete a course', function () {
    $course = Course::factory()->create();

    delete(route('course.destroy', $course))->assertRedirect();

    assertDatabaseMissing('courses', ['id' => $course->id]);
});