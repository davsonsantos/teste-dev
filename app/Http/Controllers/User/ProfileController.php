<?php

namespace App\Http\Controllers\User;

use App\Actions\User\UpdateUserAction;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Profile/Index');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // Max 2MB
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {

            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar_path' => $path]);
        }

        return back()->with('success', 'Avatar atualizado com sucesso!');
    }

    public function updateInformation(Request $request, UpdateUserAction $action)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255']
        ]);

        $action->execute($user, $validated);
        return back()->with([
            'success' => 'Informações atualizada!',
            'display' => 'toast',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'], 
            'password' => ['required', 'confirmed'],
        ]);

        if (!\Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with([
            'success' => 'Senha atualizada!',
            'display' => 'toast',
        ]);
    }

    public function update2FA(Request $request)
    {
        $validated = $request->validate([
            'enable_2fa' => ['required', 'boolean'],
        ]);

        $request->user()->update([
            'enable_2fa' => $validated['enable_2fa'],
        ]);
    }
}
