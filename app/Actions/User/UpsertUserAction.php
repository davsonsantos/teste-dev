<?php

namespace App\Actions\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpsertUserAction
{
    public function execute(array $data, ?User $user = null): User
    {
        return DB::transaction(function () use ($data, $user) {
            
            // Prepara os atributos básicos
            $attributes = [
                'name'       => $data['name'],
                'email'      => $data['email'],
                'role'       => $data['role'],
                'is_active'  => $data['is_active'] ?? true,
                'birth_date' => !empty($data['birth_date']) ? Carbon::parse($data['birth_date'])->format('Y-m-d') : null,
            ];

            // Só faz o hash e atualiza a senha se ela foi de fato enviada
            if (!empty($data['password'])) {
                $attributes['password'] = Hash::make($data['password']);
            }

            // Se o usuário existir, atualiza. Se não, cria um novo.
            if ($user) {
                $user->update($attributes);
                return $user;
            }

            return User::create($attributes);
        });
    }
}