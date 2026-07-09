Restablecé tu contraseña en Impulsa

@if ($nombre)
Hola {{ $nombre }}, recibimos una solicitud para restablecer la contraseña de tu cuenta.
@else
Recibimos una solicitud para restablecer la contraseña de tu cuenta.
@endif

Correo: {{ $correo }}

Usá este enlace para elegir una nueva contraseña:

{{ $link }}

Este enlace expira en {{ config('impulsa.password_reset_token_ttl_minutes') }} minutos. Si no solicitaste este cambio, ignorá este mensaje.
