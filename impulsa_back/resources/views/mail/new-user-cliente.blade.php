@extends('mail.layouts.impulsa')

@section('content')
  <h1 style="margin: 0 0 12px; font-size: 24px; color: #12325b;">Tu acceso a Impulsa</h1>
  <p style="margin: 0 0 16px; line-height: 1.6; color: #18202f;">
    Hola {{ $nombre }}, creamos tu usuario para que puedas ingresar al panel y seguir el avance de tu proyecto.
  </p>

  <div style="background: #f7f9fe; border: 1px solid #dde3ef; border-radius: 10px; padding: 16px; margin: 20px 0;">
    <p style="margin: 0 0 8px;"><strong>Usuario:</strong> {{ $correo }}</p>
    <p style="margin: 0;"><strong>Contraseña:</strong> {{ $password }}</p>
  </div>

  <p style="margin: 0 0 20px;">
    <a
      href="{{ $link }}"
      style="display: inline-block; padding: 12px 18px; background: #1f5eff; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700;"
    >
      Ingresar
    </a>
  </p>

  <p style="margin: 0 0 12px; color: #667085; font-size: 13px;">Link de acceso: {{ $link }}</p>
  <p style="margin: 0; color: #667085; font-size: 13px;">Por seguridad, conservá estas credenciales en un lugar privado.</p>
@endsection
