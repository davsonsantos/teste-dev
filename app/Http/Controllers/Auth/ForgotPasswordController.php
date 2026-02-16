<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email']
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Insira um endereço de e-mail válido.',
        ]);

        // O Broker cuida da criação do token e chama o método sendPasswordResetNotification que criamos
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'E-mail enviado para o e-mail informado, não esquece de verificar a caixa de spam');
        }

        return back()->withErrors(['email' => 'Não conseguimos encontrar um usuário com este e-mail.']);
    }
}
