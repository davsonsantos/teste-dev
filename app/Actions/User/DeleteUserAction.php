<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DeleteUserAction
{
    public function execute(User $user): bool
    {
        // Regra de negócio: remover avatar ao deletar
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        return $user->delete();
    }
}
