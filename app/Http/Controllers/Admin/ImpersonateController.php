<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImpersonateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function __construct(
        protected ImpersonateService $service
    ) {}

    public function take(User $user): RedirectResponse
    {
        session()->put('impersonator_user_id', Auth::id());
        Auth::login($user);
        return redirect()->route('dashboard')->with('success', "Você agora é {$user->name}");
    }

    public function leave(): RedirectResponse
    {
        $adminId = session()->pull('impersonator_user_id');

        if ($adminId) {
            $admin = User::find($adminId);
            Auth::login($admin);
        }

        return redirect()->route('users.index')->with('success', 'Você voltou para sua conta original.');
    }
}
