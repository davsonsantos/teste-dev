@extends('emails.layouts.default')

@section('content')
<p>Olá, <strong>{{ $user->name }}</strong>!</p>

<p>Você recebeu este e-mail porque solicitou a redefinição de senha da sua conta no {{ config('app.name') }}.</p>

<p>Para prosseguir, clique no botão abaixo:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $url }}"
        style="background-color: #c2410c; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;">
        Redefinir Senha
    </a>
</div>

<p>Este link expira em 60 minutos. Se você não solicitou esta alteração, ignore este e-mail.</p>

<hr>
Atenciosamente,<br>
<p><strong>Equipe {{ config('app.name') }}</strong></p>
@endsection