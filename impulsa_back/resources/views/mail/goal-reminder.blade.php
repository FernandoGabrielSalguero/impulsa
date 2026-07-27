@extends('mail.layouts.impulsa')

@section('content')
  <h1 style="margin: 0 0 12px; font-size: 24px; color: #12325b;">{{ $reminderLabel }}</h1>
  <p style="margin: 0 0 16px; line-height: 1.6; color: #18202f;">
    Hola {{ $userName }},
    @if ($objectiveTitle)
      el objetivo <strong>"{{ $objectiveTitle }}"</strong> de tu meta <strong>{{ $goalTitle }}</strong>
    @else
      tu meta <strong>{{ $goalTitle }}</strong>
    @endif
    @if ($reminderKind === 'upcoming_1d')
      vence mañana.
    @else
      está vencida.
    @endif
  </p>

  <div style="background: #eef4ff; border: 1px solid #c7d7fe; border-radius: 10px; padding: 16px; margin: 20px 0;">
    <p style="margin: 0 0 8px;"><strong>Fecha límite:</strong> {{ $dueDateLabel }}</p>
    <p style="margin: 0 0 8px;"><strong>Avance actual:</strong> {{ $progressPercent }}%</p>
    <p style="margin: 0; color: #475467;">{{ $progressDetail }}</p>
  </div>

  <p style="margin: 0 0 20px;">
    <a href="{{ $metasUrl }}" style="display: inline-block; padding: 12px 18px; background: #1f5eff; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700;">
      Revisar meta
    </a>
  </p>
@endsection
