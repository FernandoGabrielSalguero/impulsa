<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$usuarios = $marketingUsuarios ?? [];
$maskValue = static fn (?string $valor, bool $permitido): string => $permitido ? (trim((string) $valor) !== '' ? (string) $valor : '-') : '****';
$nombreVisible = static function (array $usuario): string {
    $nombreCompleto = trim((string) ($usuario['nombre'] ?? '') . ' ' . (string) ($usuario['apellido'] ?? ''));
    if ($nombreCompleto !== '') {
        return $nombreCompleto;
    }

    $apodo = trim((string) ($usuario['apodo'] ?? ''));
    if ($apodo !== '') {
        return $apodo;
    }

    return (string) ($usuario['correo_login'] ?? 'Sin nombre');
};
?>
<section class="marketing-users-panel">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Usuarios</p>
      <h2>Usuarios externos de la plataforma</h2>
      <p>Listado visible para marketing con datos de contacto protegidos cuando el usuario desactivo permisos.</p>
    </div>
    <span class="im-chip"><?= number_format(count($usuarios), 0, ',', '.') ?> usuarios</span>
  </div>

  <article class="im-tabla-tareas__tarjeta">
    <div class="im-tabla-tareas__cabecera">
      <div>
        <h3>Base de usuarios</h3>
        <p>Se muestran solo registros con <code>usuario_tipo = externo</code>.</p>
      </div>
    </div>
    <div class="im-tabla-tareas__scroll">
      <table class="im-tabla-tareas">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Proyecto</th>
            <th>Correo</th>
            <th>Telefono</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $usuarioItem): ?>
            <?php
            $permiteCorreo = (int) ($usuarioItem['permison_correo'] ?? 1) === 1;
            $permiteWhatsapp = (int) ($usuarioItem['permison_whatsapp'] ?? 1) === 1;
            ?>
            <tr>
              <td class="im-tabla-tareas__nombre"><?= $h($nombreVisible($usuarioItem)) ?></td>
              <td><?= $h($usuarioItem['project_name'] ?? 'Sin proyecto') ?></td>
              <td><?= $h($maskValue((string) ($usuarioItem['correo_contacto'] ?? $usuarioItem['correo_login'] ?? ''), $permiteCorreo)) ?></td>
              <td><?= $h($maskValue((string) ($usuarioItem['whatsapp'] ?? ''), $permiteWhatsapp)) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$usuarios): ?>
            <tr><td colspan="4">No hay usuarios externos para mostrar.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </article>
</section>
