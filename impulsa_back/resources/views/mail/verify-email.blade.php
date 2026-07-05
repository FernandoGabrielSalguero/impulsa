@extends('mail.layouts.impulsa')

@section('content')
    <h1>Verificá tu dirección de correo</h1>
    <p>Gracias por registrarte. Para activar tu cuenta confirmá que esta dirección te pertenece:</p>

    <span class="correo-tag">{{ $correo }}</span>

    <p>Hacé clic en el botón para verificarla:</p>
    <a class="btn" href="{{ $link }}">Verificar correo</a>

    <p class="link-fallback">
        Si el botón no funciona, copiá y pegá este enlace en tu navegador:<br>
        <a href="{{ $link }}">{{ $link }}</a>
    </p>

    <hr class="divider">

    <p class="muted">Este enlace expira en {{ config('impulsa.verification_token_ttl_hours') }} horas. Si no creaste una cuenta en Impulsa, podés ignorar este mensaje sin problema.</p>
@endsection