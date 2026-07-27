@extends('mail.layouts.impulsa')

@section('content')
  <h1 style="margin: 0 0 12px; font-size: 24px; color: #12325b;">Objetivo completado</h1>
  <p style="margin: 0 0 16px; line-height: 1.6; color: #18202f;">
    Hola {{ $userName }}, completaste el objetivo <strong>"{{ $objectiveTitle }}"</strong> en tu meta <strong>{{ $goalTitle }}</strong>.
  </p>

  <div style="background: #eef4ff; border: 1px solid #c7d7fe; border-radius: 10px; padding: 16px; margin: 20px 0;">
    <p style="margin: 0 0 8px;"><strong>Avance de la meta:</strong> {{ $progressPercent }}%</p>
    <p style="margin: 0 0 8px;"><strong>Fecha límite de la meta:</strong> {{ $dueDateLabel }}</p>
    @if ($remainingObjectives > 0)
      <p style="margin: 0 0 8px;"><strong>Objetivos restantes:</strong> {{ $remainingObjectives }}</p>
    @endif
    <p style="margin: 0; color: #475467;">{{ $progressDetail }}</p>
  </div>

  <p style="margin: 0 0 20px;">
    <a href="{{ $metasUrl }}" style="display: inline-block; padding: 12px 18px; background: #1f5eff; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700;">
      Ver mi meta
    </a>
  </p>
@endsection
