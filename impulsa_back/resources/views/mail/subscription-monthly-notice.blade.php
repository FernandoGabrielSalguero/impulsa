@extends('mail.layouts.impulsa')

@section('content')
    <h1>Aviso de suscripción mensual</h1>
    <p>Te informamos el monto correspondiente a la mensualidad de tu sitio web <strong>{{ $project_name }}</strong> para el período <strong>{{ $period }}</strong>.</p>

    <p>Monto informado: <strong>${{ $amount }} ARS</strong></p>

    <p>El cobro se gestiona de forma segura a través de Mercado Pago. Si aún no regularizaste el pago de este mes, podés hacerlo desde el siguiente enlace:</p>

    <a class="btn" href="{{ $payment_url }}">Pagar suscripción en Mercado Pago</a>

    <p class="link-fallback">
        Si el botón no funciona, copiá este enlace:<br>
        <a href="{{ $payment_url }}">{{ $payment_url }}</a>
    </p>

    <hr class="divider">

    <p class="muted">Si ya abonaste la suscripción, podés ignorar este mensaje. Mercado Pago confirmará el pago automáticamente.</p>
@endsection
