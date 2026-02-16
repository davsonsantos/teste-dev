<?php

namespace App\Http\Controllers\User;

use App\Actions\User\UpsertUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSortFields = ['name', 'email', 'role', 'created_at'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $users = User::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->input('status'), function ($query, $status) {
                if ($status === 'active') $query->where('is_active', true);
                if ($status === 'inactive') $query->where('is_active', false);
            })
            ->when($request->input('role'), function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($request->input('trashed'), function ($query, $trashed) {
                if ($trashed === 'true') $query->onlyTrashed();
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'status', 'sort_field', 'sort_direction']),
        ]);
    }


    public function store(Request $request, UpsertUserAction $action)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'   => ['required'],
            'is_active' => ['boolean'],
            'birth_date' => ['nullable', 'date'],
            'password'  => ['required', Password::defaults()]
        ]);

        $action->execute($data);
        return back()->with(['success' => 'Usuário criado!', 'display' => 'toast']);
    }

    public function update(Request $request, User $user, UpsertUserAction $action)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'   => ['required'],
            'is_active' => ['boolean'],
            'birth_date' => ['nullable', 'date'],
            'password'  => ['nullable', Password::defaults()],
        ]);

        $action->execute($data, $user);

        return back()->with(['success' => 'Usuário atualizado!', 'display' => 'toast']);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }


        $user->delete();
        return back()->with('success', 'Usuário enviado para a lixeira!');
    }
}
