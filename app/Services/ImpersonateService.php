<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonateService
{
    private const SESSION_KEY = 'impersonator_user_id';
    public function startImpersonation(User $targetUser): void
    {
        // Armazena o ID do admin original na sessão
        Session::put(self::SESSION_KEY, Auth::id());

        // Realiza o login como o novo usuário
        Auth::login($targetUser);
    }

    public function stopImpersonation(): void
    {
        $adminId = Session::pull(self::SESSION_KEY);

        if ($adminId) {
            $admin = User::find($adminId);
            Auth::login($admin);
        }
    }

    public function isImpersonating(): bool
    {
        return Session::has(self::SESSION_KEY);
    }
}
