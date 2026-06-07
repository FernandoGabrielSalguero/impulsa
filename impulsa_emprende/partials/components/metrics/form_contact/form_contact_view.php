<?php
$formContactRows = $formContactRows ?? [];
$formContactFecha = static function (?string $valor): string {
    $valor = trim((string) $valor);
    if ($valor === '') {
        return '-';
    }

    $timestamp = strtotime($valor);
    return $timestamp ? date('d/m/Y H:i', $timestamp) : $valor;
};
$formContactText = static function (?string $valor): string {
    $valor = trim((string) $valor);
    return $valor !== '' ? $valor : '-';
};
?>
<article class="im-tabla-tareas__tarjeta">
  <div class="im-tabla-tareas__cabecera">
    <div>
      <h3>Contactos recibidos</h3>
      <p>Consultas registradas desde tu página web desde el formulario de contacto.</p>
    </div>
    <span class="im-chip"><?= number_format(count($formContactRows), 0, ',', '.') ?> contactos</span>
  </div>
  <?php if (!$formContactRows): ?>
    <div class="im-alerta im-alerta--info">Todavia no hay contactos registrados en su página web.</div>
  <?php else: ?>
    <div class="im-tabla-tareas__scroll">
      <table class="im-tabla-tareas">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Proyecto</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>WhatsApp</th>
            <th>Pagina</th>
            <th>Consulta</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($formContactRows as $contacto): ?>
            <tr>
              <td><?= htmlspecialchars($formContactFecha($contacto['created_at'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td class="im-tabla-tareas__nombre">
                <?= htmlspecialchars((string) ($contacto['project_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>
                <br><small><?= htmlspecialchars((string) ($contacto['allowed_domain'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></small>
              </td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_nombre'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_email'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_whatsapp'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contacto['page'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_description'] ?? $contacto['contact_consultation'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><span class="im-chip"><?= htmlspecialchars($formContactText($contacto['state'] ?? null), ENT_QUOTES, 'UTF-8') ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</article>
