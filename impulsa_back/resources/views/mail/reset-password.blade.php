@extends('mail.layouts.impulsa')

@section('content')
    <h1>Restablecé tu contraseña</h1>

    @if ($nombre)
        <p>Hola {{ $nombre }}, recibimos una solicitud para restablecer la contraseña de tu cuenta en Impulsa.</p>
    @else
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en Impulsa.</p>
    @endif

    <span class="correo-tag">{{ $correo }}</span>

    <p>Hacé clic en el botón para elegir una nueva contraseña:</p>
    <a class="btn" href="{{ $link }}">Restablecer contraseña</a>

    <p class="link-fallback">
        Si el botón no funciona, copiá y pegá este enlace en tu navegador:<br>
        <a href="{{ $link }}">{{ $link }}</a>
    </p>

    <hr class="divider">

    <p class="muted">Este enlace expira en {{ config('impulsa.password_reset_token_ttl_minutes') }} minutos. Si no solicitaste este cambio, podés ignorar este mensaje sin problema.</p>
@endsection
