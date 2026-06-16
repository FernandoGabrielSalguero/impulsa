<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$usuarios = $marketingUsuarios ?? [];
$maskValue = static fn (?string $valor, bool $permitido): string => $permitido ? (trim((string) $valor) !== '' ? (string) $valor : '-') : '****';
$toJson = static fn (mixed $valor): string => htmlspecialchars(json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
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
            <th>Emprendimiento</th>
            <th>Proyecto</th>
            <th>Correo</th>
            <th>Telefono</th>
            <th class="im-tabla-tareas__acciones">Acciones</th>
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
              <td><?= $h($usuarioItem['nombre_emprendimiento'] ?? 'Sin definir') ?></td>
              <td><?= $h($usuarioItem['project_name'] ?? 'Sin proyecto') ?></td>
              <td><?= $h($maskValue((string) ($usuarioItem['correo_contacto'] ?? $usuarioItem['correo_login'] ?? ''), $permiteCorreo)) ?></td>
              <td><?= $h($maskValue((string) ($usuarioItem['whatsapp'] ?? ''), $permiteWhatsapp)) ?></td>
              <td class="im-tabla-tareas__acciones">
                <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-marketing-user-detail="<?= $toJson($usuarioItem) ?>" aria-label="Ver detalle del usuario" data-tooltip="Ver detalle">visibility</button>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$usuarios): ?>
            <tr><td colspan="6">No hay usuarios externos para mostrar.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </article>

  <div class="im-modal-cortina im-drawer-cortina" data-marketing-user-backdrop></div>
  <aside class="im-drawer marketing-user-detail-drawer" role="dialog" aria-modal="true" aria-labelledby="marketing-user-detail-title" aria-hidden="true" data-marketing-user-drawer>
    <header class="im-drawer__cabecera">
      <div>
        <p class="im-sobrelinea">Usuario externo</p>
        <h3 id="marketing-user-detail-title">Detalle del usuario</h3>
      </div>
      <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-close-user-detail aria-label="Cerrar dialog">close</button>
    </header>
    <div class="im-drawer__contenido" data-marketing-user-detail-content></div>
    <footer class="im-drawer__acciones">
      <button class="im-boton im-boton--tonal" type="button" data-marketing-close-user-detail>Cerrar</button>
    </footer>
  </aside>
</section>

<script>
  (() => {
    const drawer = document.querySelector('[data-marketing-user-drawer]');
    const backdrop = document.querySelector('[data-marketing-user-backdrop]');
    const content = document.querySelector('[data-marketing-user-detail-content]');
    if (!drawer || !backdrop || !content) {
      return;
    }

    const escapeHtml = (value) => String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const statusChip = (active, yes = 'Completo', no = 'Pendiente') => `<span class="im-chip ${active ? 'im-chip--exito' : 'im-chip--alerta'}">${active ? yes : no}</span>`;
    const detailStat = (label, value) => `
      <article class="marketing-user-detail__stat">
        <strong>${escapeHtml(label)}</strong>
        <div>${value}</div>
      </article>
    `;
    const detailRow = (label, value) => `
      <div class="marketing-user-detail__row">
        <dt>${escapeHtml(label)}</dt>
        <dd>${value}</dd>
      </div>
    `;
    const displayName = (data) => {
      const nombre = `${data.nombre || ''} ${data.apellido || ''}`.trim();
      if (nombre) {
        return nombre;
      }
      if (data.apodo) {
        return String(data.apodo);
      }
      return String(data.correo_login || 'Sin nombre');
    };

    const openDrawer = (data) => {
      const projectName = data.project_name || 'Sin proyecto';
      const emprendimiento = data.nombre_emprendimiento || 'Sin definir';
      const contactEmail = Number(data.permison_correo || 1) === 1 ? (data.correo_contacto || data.correo_login || '-') : '****';
      const contactWhatsapp = Number(data.permison_whatsapp || 1) === 1 ? (data.whatsapp || '-') : '****';
      const role = String(data.rol || '').replaceAll('_', ' ');
      const hasProject = Boolean(data.project_name);
      const hasLandingRequest = Boolean(data.nombre_emprendimiento);
      const formattedRole = role ? role.charAt(0).toUpperCase() + role.slice(1) : 'Usuario externo';
      content.innerHTML = `
        <article class="marketing-user-detail">
          <div class="marketing-user-detail__hero">
            <div class="marketing-user-detail__hero-copy">
              <div class="marketing-user-detail__hero-chips">
                <span class="im-chip">${escapeHtml(formattedRole)}</span>
                <span class="im-chip im-chip--tonal">${hasProject ? 'Con proyecto' : 'Sin proyecto'}</span>
              </div>
              <h2>${escapeHtml(displayName(data))}</h2>
              <p>${escapeHtml(emprendimiento)}</p>
            </div>
          </div>
          <div class="marketing-user-detail__summary">
            ${detailStat('Proyecto activo', escapeHtml(projectName))}
            ${detailStat('Tipo de proyecto', escapeHtml(data.project_type || '-'))}
            ${detailStat('Estado del proyecto', escapeHtml(data.project_status || '-'))}
            ${detailStat('Email verificado', statusChip(Boolean(data.email_verified_at), 'Verificado', 'Pendiente'))}
          </div>
          <section class="marketing-user-detail__section-card">
            <div class="marketing-user-detail__section-head">
              <h4>Contacto</h4>
              <p>Datos visibles segun los permisos definidos por el usuario.</p>
            </div>
            <dl class="marketing-user-detail__list">
              ${detailRow('Correo', escapeHtml(contactEmail))}
              ${detailRow('Telefono', escapeHtml(contactWhatsapp))}
            </dl>
          </section>
          <section class="marketing-user-detail__section-card">
            <div class="marketing-user-detail__section-head">
              <h4>Perfil emprendedor</h4>
              <p>Base minima para conocer el nivel de avance y definicion del negocio.</p>
            </div>
            <div class="marketing-user-detail__checks">
              ${detailStat('Mision', statusChip(Number(data.has_mision || 0) === 1))}
              ${detailStat('Vision', statusChip(Number(data.has_vision || 0) === 1))}
              ${detailStat('Buyer persona', statusChip(Number(data.has_buyer_persona || 0) === 1))}
              ${detailStat('Pagina web solicitada', statusChip(hasLandingRequest, 'Solicitada', 'Sin solicitud'))}
            </div>
          </section>
          <section class="marketing-user-detail__section-card">
            <div class="marketing-user-detail__section-head">
              <h4>Solicitud web</h4>
              <p>Estado general y datos comerciales que ayudan a contextualizar la necesidad.</p>
            </div>
            <div class="marketing-user-detail__checks">
              ${detailStat('Estado', statusChip(Number(data.landing_completado || 0) === 1, 'Completa', 'En proceso'))}
              ${detailStat('Fecha estimada', escapeHtml(data.fecha_inicio || '-'))}
              ${detailStat('Vende productos', statusChip(Number(data.vende_productos || 0) === 1, 'Si', 'No'))}
              ${detailStat('Vende servicios', statusChip(Number(data.vende_servicios || 0) === 1, 'Si', 'No'))}
              ${detailStat('Ya factura', statusChip(Number(data.ya_factura || 0) === 1, 'Si', 'No'))}
            </div>
            ${data.landing_descripcion ? `<div class="marketing-user-detail__description"><strong>Descripcion del pedido</strong><p>${escapeHtml(data.landing_descripcion)}</p></div>` : ''}
          </section>
        </article>
      `;
      backdrop.classList.add('abierto');
      drawer.classList.add('abierto');
      drawer.setAttribute('aria-hidden', 'false');
    };

    const closeDrawer = () => {
      drawer.classList.remove('abierto');
      drawer.setAttribute('aria-hidden', 'true');
      backdrop.classList.remove('abierto');
    };

    document.addEventListener('click', (event) => {
      const trigger = event.target.closest('[data-marketing-user-detail]');
      if (trigger) {
        try {
          openDrawer(JSON.parse(trigger.getAttribute('data-marketing-user-detail') || '{}'));
        } catch (error) {
          return;
        }
      }
      if (event.target.closest('[data-marketing-close-user-detail], [data-marketing-user-backdrop]')) {
        closeDrawer();
      }
    });
  })();
</script>
