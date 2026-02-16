<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use function Pest\Laravel\{actingAs, get, post, put, delete, assertDatabaseHas, assertDatabaseMissing};

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    actingAs($this->admin);
});

it('can render the enrollments index page', function () {
    get(route('enrollments.index'))->assertOk();
});

it('can create a new enrollment', function () {
    $this->withoutExceptionHandling();
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::factory()->create();

    $enrollmentData = [
        'user_id'   => $student->id,
        'course_id' => $course->id,
        'status'    => 'cursando',
    ];

    post(route('enrollments.store'), $enrollmentData)->assertRedirect();

    assertDatabaseHas('enrollments', [
        'user_id'   => $student->id,
        'course_id' => $course->id,
        'status'    => 'cursando',
        'completed_at' => null,
    ]);
});

it('prevents duplicate enrollments for the same student and course', function () {
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::factory()->create();

    // Cria a primeira matrícula diretamente no banco
    Enrollment::factory()->create([
        'user_id'   => $student->id,
        'course_id' => $course->id,
    ]);

    $duplicateData = [
        'user_id'   => $student->id,
        'course_id' => $course->id,
        'status'    => 'pendente',
    ];

    // Tenta fazer a mesma matrícula via POST
    post(route('enrollments.store'), $duplicateData)
        ->assertSessionHasErrors('course_id'); // A regra de validação deve falhar aqui
});

it('auto-fills completed_at when status is updated to concluido', function () {
    $enrollment = Enrollment::factory()->create(['status' => 'cursando', 'completed_at' => null]);

    $updateData = [
        'status' => 'concluido',
    ];

    put(route('enrollments.update', $enrollment), $updateData)->assertRedirect();

    // Verificamos o banco de dados sem passar 'completed_at', pois ele é gerado dinamicamente (now())
    // Apenas garantimos que o status mudou. O assertDatabaseHas com now() pode falhar por milissegundos.
    $enrollment->refresh();
    expect($enrollment->status)->toBe('concluido');
    expect($enrollment->completed_at)->not->toBeNull();
});

it('can delete an enrollment', function () {
    $enrollment = Enrollment::factory()->create();

    delete(route('enrollments.destroy', $enrollment))->assertRedirect();

    assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
});
