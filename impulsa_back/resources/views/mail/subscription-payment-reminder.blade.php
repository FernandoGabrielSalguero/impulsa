@extends('mail.layouts.impulsa')

@section('content')
    <h1>Recordatorio de pago pendiente</h1>
    <p>Tu sitio web <strong>{{ $project_name }}</strong> tiene una suscripción pendiente para el período <strong>{{ $period }}</strong>.</p>

    <p>Monto informado: <strong>${{ $amount }} ARS</strong></p>

    <p>Regularizá el pago en Mercado Pago para evitar interrupciones del servicio:</p>

    <a class="btn" href="{{ $payment_url }}">Pagar ahora en Mercado Pago</a>

    <p class="link-fallback">
        Enlace directo:<br>
        <a href="{{ $payment_url }}">{{ $payment_url }}</a>
    </p>

    <hr class="divider">

    <p class="muted">A partir del día 15 del mes, el sitio puede mostrarse como no disponible hasta registrar el pago.</p>
@endsection
