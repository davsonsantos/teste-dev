@extends('emails.layouts.default')

@section('content')
<h1 style="color: #333;">Olá!</h1>
<p>Você foi convidado para fazer parte da plataforma <strong>{{ config('app.name') }}</strong>.</p>

<p>Para concluir seu cadastro e definir sua senha, clique no botão abaixo:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $url }}"
        style="background-color: #f97316; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
        Aceitar Convite e Cadastrar
    </a>
</div>

<p style="font-size: 12px; color: #777;">
    Este link é válido por 24 horas. Se você não esperava este convite, pode ignorar este e-mail.
</p>

<hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

<p>Atenciosamente,</p>
<p><strong>Equipe {{ config('app.name') }}</strong></p>
@endsection