{{ $reminderLabel }}

Hola {{ $userName }},
@if ($objectiveTitle)
el objetivo "{{ $objectiveTitle }}" de tu meta {{ $goalTitle }}
@else
tu meta {{ $goalTitle }}
@endif
@if ($reminderKind === 'upcoming_1d')
vence mañana.
@else
está vencida.
@endif

Fecha límite: {{ $dueDateLabel }}
Avance actual: {{ $progressPercent }}%
{{ $progressDetail }}

Revisar meta: {{ $metasUrl }}
