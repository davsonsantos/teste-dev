<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInviteMailNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvateController extends Controller
{
    public function invite(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email', 'unique:users,email']
        ]);

        // Geramos uma URL que expira em 24 horas
        // O ID do indicador (auth()->id()) é embutido na URL assinada
        $inviteUrl = URL::temporarySignedRoute(
            'register.invited', // Nome da rota de cadastro
            now()->addDays(1),
            ['referrer' => auth()->id(), 'email' => $request->email]
        );

        // Enviar o e-mail (Exemplo simplificado)
        Mail::to($request->email)->send(new UserInviteMailNotification($inviteUrl));

        return back()->with('success', 'Convite enviado com sucesso para ' . $request->email);
    }

    public function registerInvited(Request $request)
    {
        $inviter = User::find($request->query('referrer'));
        return Inertia::render('Auth/RegisterInvited', [
            'email' => $request->query('email'),
            'referrer_id' => $request->query('referrer'),
            'inviter_name' => $inviter ? $inviter->name : 'Nossa Equipe',
        ]);
    }
}
