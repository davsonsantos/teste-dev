<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        // 1. Definição e Validação da Ordenação (Campos permitidos para evitar SQL Injection)
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $allowedSortFields = ['name', 'email', 'role', 'created_at'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // 2. Construção da Query com Escopo de Filtros e Ordenação Dinâmica
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
            ->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'status', 'sort_field', 'sort_direction']),
        ]);
    }

    // No UsersController.php

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'   => ['required'],
            'is_active' => ['boolean'],
            'birth_date' => ['nullable', 'date'],
            'password'  => ['required', Password::defaults()]
        ]);

        User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'is_active'  => $data['is_active'] ?? true,
            'role'       => $data['role'],
            'birth_date' => !empty($data['birth_date']) ? Carbon::parse($data['birth_date'])->format('Y-m-d') : null,
            'password'   => Hash::make($data['password'])
        ]);
        return back()->with(['success' => 'Usuário criado!', 'display' => 'toast']);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'   => ['required'],
            'is_active' => ['boolean'],
            'birth_date' => ['nullable', 'date'],
            'password'  => ['nullable', Password::defaults()],
        ]);

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'is_active' => $data['is_active'],
            'role'      => $data['role'],
            'birth_date' => !empty($data['birth_date']) ? Carbon::parse($data['birth_date'])->format('Y-m-d') : null,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return back()->with(['success' => 'Usuário atualizado!', 'display' => 'toast']);
    }

    public function destroy(User $user)
    {
        // Impede que o usuário se exclua
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }


        $user->delete();
        return back()->with('success', 'Usuário enviado para a lixeira!');
    }
}
