<?php

namespace App\Actions\User;

use App\Models\User;
use App\DTOs\UserDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name'  => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
            ]);

            return $user;
        });
    }
}
