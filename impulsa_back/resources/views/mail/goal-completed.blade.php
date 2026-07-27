@extends('mail.layouts.impulsa')

@section('content')
  <h1 style="margin: 0 0 12px; font-size: 24px; color: #12325b;">Meta completada</h1>
  <p style="margin: 0 0 16px; line-height: 1.6; color: #18202f;">
    Hola {{ $userName }}, felicitaciones. Completaste todos los objetivos de tu meta <strong>{{ $goalTitle }}</strong>.
  </p>

  <div style="background: #eef4ff; border: 1px solid #c7d7fe; border-radius: 10px; padding: 16px; margin: 20px 0;">
    <p style="margin: 0 0 8px;"><strong>Inicio:</strong> {{ $startDateLabel }}</p>
    <p style="margin: 0 0 8px;"><strong>Completada:</strong> {{ $completedDateLabel }}</p>
    <p style="margin: 0; color: #475467;">{{ $progressDetail }}</p>
  </div>

  @if (count($completedObjectives) > 0)
    <div style="background: #f7f9fe; border: 1px solid #dde3ef; border-radius: 10px; padding: 16px; margin: 20px 0;">
      <p style="margin: 0 0 10px; font-weight: 700; color: #12325b;">Objetivos cumplidos</p>
      <ul style="margin: 0; padding-left: 18px; color: #18202f; line-height: 1.6;">
        @foreach ($completedObjectives as $line)
          <li style="margin-bottom: 6px;">{{ $line }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <p style="margin: 0 0 20px;">
    <a href="{{ $metasUrl }}" style="display: inline-block; padding: 12px 18px; background: #1f5eff; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700;">
      Ver mis metas
    </a>
  </p>
@endsection
