Actualización de tu proyecto · {{ $projectName }}

Hola {{ $clientName }}, hay novedades en tu proyecto "{{ $projectName }}".

@if ($updateMessage !== '')
{{ $updateMessage }}

@endif
@if (count($changeLines) > 0)
Detalle de cambios:
@foreach ($changeLines as $line)
- {{ $line }}
@endforeach

@endif
Avance actual: {{ $progressPercent }}%
Estado del proyecto: {{ $statusLabel }}
{{ $progressDetail }}

Ver tu proyecto: {{ $dashboardUrl }}
