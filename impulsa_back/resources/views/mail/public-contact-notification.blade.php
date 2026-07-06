@extends('mail.layouts.impulsa')

@section('content')
    <h1>Nuevo contacto recibido</h1>
    <p>Recibiste un nuevo mensaje desde la web pública de <strong>{{ $project_name }}</strong> ({{ $allowed_domain }}).</p>

    <p><strong>Nombre:</strong> {{ $contact_nombre }}</p>
    <p><strong>Email:</strong> {{ $contact_email }}</p>
    <p><strong>WhatsApp:</strong> {{ $contact_whatsapp }}</p>
    <p><strong>Página:</strong> {{ $page }}</p>
    <p><strong>Mensaje:</strong> {{ $message_excerpt }}</p>

    <a class="btn" href="{{ $panel_url }}">Ver contacto en Impulsa</a>

    <p class="link-fallback">
        Si el botón no funciona, copiá este enlace:<br>
        <a href="{{ $panel_url }}">{{ $panel_url }}</a>
    </p>

    <hr class="divider">

    <p class="muted">Este aviso se envió porque tu sitio está integrado con Impulsa Emprende.</p>
@endsection
