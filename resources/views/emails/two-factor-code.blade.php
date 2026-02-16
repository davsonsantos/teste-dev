@extends('emails.layouts.default')

@section('content')
<p>Olá, {{ $userName }}!,</p>
<p>Você está tentando acessar sua conta no **{{ config('app.name') }}**. Para garantir sua segurança, utilize o código
    de verificação abaixo para concluir seu login:</p>

<h1 style="text-align: center; letter-spacing: 5px; font-size: 32px; color: #14532d;">
    {{ $code }}
</h1>

<p>Este código é válido por **10 minutos**. Se você não solicitou este acesso, por favor, ignore este e-mail ou altere
    sua senha imediatamente.</p>

Atenciosamente,<br>
<p><strong>Equipe {{ config('app.name') }}</strong></p>
@endsection