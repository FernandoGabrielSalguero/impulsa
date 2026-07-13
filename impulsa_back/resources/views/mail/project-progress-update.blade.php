@extends('mail.layouts.impulsa')

@section('content')
  <h1 style="margin: 0 0 12px; font-size: 24px; color: #12325b;">{{ $updateTitle }}</h1>
  <p style="margin: 0 0 16px; line-height: 1.6; color: #18202f;">
    Hola {{ $clientName }}, hay novedades en tu proyecto <strong>{{ $projectName }}</strong>.
  </p>

  @if ($updateMessage !== '')
    <p style="margin: 0 0 16px; line-height: 1.6; color: #18202f;">{{ $updateMessage }}</p>
  @endif

  @if (count($changeLines) > 0)
    <div style="background: #f7f9fe; border: 1px solid #dde3ef; border-radius: 10px; padding: 16px; margin: 20px 0;">
      <p style="margin: 0 0 10px; font-weight: 700; color: #12325b;">Detalle de cambios</p>
      <ul style="margin: 0; padding-left: 18px; color: #18202f; line-height: 1.6;">
        @foreach ($changeLines as $line)
          <li style="margin-bottom: 6px;">{{ $line }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div style="background: #eef4ff; border: 1px solid #c7d7fe; border-radius: 10px; padding: 16px; margin: 20px 0;">
    <p style="margin: 0 0 8px;"><strong>Avance actual:</strong> {{ $progressPercent }}%</p>
    <p style="margin: 0 0 8px;"><strong>Estado del proyecto:</strong> {{ $statusLabel }}</p>
    <p style="margin: 0; color: #475467;">{{ $progressDetail }}</p>
  </div>

  <p style="margin: 0 0 20px;">
    <a
      href="{{ $dashboardUrl }}"
      style="display: inline-block; padding: 12px 18px; background: #1f5eff; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700;"
    >
      Ver mi proyecto
    </a>
  </p>

  <p style="margin: 0; color: #667085; font-size: 13px;">Podés ingresar al panel para ver el detalle completo del avance.</p>
@endsection
