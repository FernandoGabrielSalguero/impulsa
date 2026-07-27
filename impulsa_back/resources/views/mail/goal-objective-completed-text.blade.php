Objetivo completado

Hola {{ $userName }}, completaste el objetivo "{{ $objectiveTitle }}" en tu meta {{ $goalTitle }}.

Avance de la meta: {{ $progressPercent }}%
Fecha límite de la meta: {{ $dueDateLabel }}
@if ($remainingObjectives > 0)
Objetivos restantes: {{ $remainingObjectives }}
@endif
{{ $progressDetail }}

Ver mi meta: {{ $metasUrl }}
