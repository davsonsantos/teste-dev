<?php

use App\Models\User;
use function Pest\Laravel\{actingAs, get, post, put, delete, assertDatabaseHas, assertSoftDeleted, assertDatabaseMissing};

beforeEach(function () {
    // Criamos um admin para agir como o usuário autenticado em todos os testes
    $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    actingAs($this->admin);
});

it('can render the users index page', function () {
    get(route('users.index'))->assertOk();
});

it('can create a new user', function () {
    $userData = [
        'name' => 'Novo Aluno',
        'email' => 'aluno@teste.com',
        'role' => 'student',
        'is_active' => true,
        'password' => 'Password@123',
    ];

    post(route('users.store'), $userData)
        ->assertRedirect()
        ->assertSessionHas('success');

    assertDatabaseHas('users', [
        'email' => 'aluno@teste.com',
        'role' => 'student',
    ]);
});

it('can update an existing user', function () {
    $user = User::factory()->create(['role' => 'student']);

    $updateData = [
        'name' => 'Nome Atualizado',
        'email' => $user->email,
        'role' => 'teacher',
        'is_active' => false,
    ];

    put(route('users.update', $user), $updateData)->assertRedirect();

    assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nome Atualizado',
        'role' => 'teacher',
        'is_active' => 0,
    ]);
});

it('prevents a user from deleting themselves', function () {
    delete(route('users.destroy', $this->admin))
        ->assertRedirect()
        ->assertSessionHas('error', 'Você não pode excluir sua própria conta.');

    assertDatabaseHas('users', ['id' => $this->admin->id]);
});

it('can soft delete a user', function () {
    $user = User::factory()->create();

    delete(route('users.destroy', $user))
        ->assertRedirect()
        ->assertSessionHas('success');

    assertSoftDeleted('users', ['id' => $user->id]);
});
