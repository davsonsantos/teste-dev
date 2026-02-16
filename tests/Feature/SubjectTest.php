<?php

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use function Pest\Laravel\{actingAs, get, post, put, delete, assertDatabaseHas, assertDatabaseMissing};

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    actingAs($this->admin);
});

it('can render the subjects index', function () {
    get(route('subject.index'))->assertOk();
});

it('can create a subject linked to a course and a teacher', function () {
    $course = Course::factory()->create();
    $teacher = User::factory()->create(['role' => 'teacher']);

    $subjectData = [
        'name' => 'Introdução a Testes',
        'description' => 'Fundamentos de qualidade de software',
        'course_id' => $course->id,
        'user_id' => $teacher->id,
    ];

    post(route('subject.store'), $subjectData)->assertRedirect();

    assertDatabaseHas('subjects', [
        'name' => 'Introdução a Testes',
        'course_id' => $course->id,
        'user_id' => $teacher->id,
    ]);
});

it('fails if course or user does not exist', function () {
    $subjectData = [
        'name' => 'Disciplina Órfã',
        'description' => 'Sem relacionamentos válidos',
        'course_id' => 9999, // ID inexistente
        'user_id' => 9999,   // ID inexistente
    ];

    post(route('subject.store'), $subjectData)
        ->assertSessionHasErrors(['course_id', 'user_id']);
});

it('can delete a subject', function () {
    $subject = Subject::factory()->create();

    delete(route('subject.destroy', $subject))->assertRedirect();

    assertDatabaseMissing('subjects', ['id' => $subject->id]);
});