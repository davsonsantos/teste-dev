<?php

namespace App\Actions\User;

use App\Models\User;
use App\DTOs\UserDTO;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(UserDTO $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password ?? 'password123'), // Default ou random
            'document' => $data->document,
            'role' => $data->role ?? 'stundent',
        ]);
    }
}
