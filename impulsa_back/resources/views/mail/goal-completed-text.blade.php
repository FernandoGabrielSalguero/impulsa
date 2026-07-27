Meta completada

Hola {{ $userName }}, felicitaciones. Completaste todos los objetivos de tu meta {{ $goalTitle }}.

Inicio: {{ $startDateLabel }}
Completada: {{ $completedDateLabel }}
{{ $progressDetail }}

@if (count($completedObjectives) > 0)
Objetivos cumplidos:
@foreach ($completedObjectives as $line)
- {{ $line }}
@endforeach
@endif

Ver mis metas: {{ $metasUrl }}
