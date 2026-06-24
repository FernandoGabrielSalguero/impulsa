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
$formContactToJson = static fn (mixed $valor): string => htmlspecialchars((string) json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
$formContactEstadoClase = static function (?string $estado): string {
    $estado = mb_strtolower(trim((string) $estado), 'UTF-8');
    return match ($estado) {
        'nuevo', 'nueva', 'pendiente' => 'im-chip--alerta',
        'contactado', 'resuelto', 'cerrado', 'completado' => 'im-chip--exito',
        default => '',
    };
};
$formContactEstados = [];
foreach ($formContactRows as $contacto) {
    $estado = trim((string) ($contacto['state'] ?? ''));
    if ($estado !== '') {
        $formContactEstados[mb_strtolower($estado, 'UTF-8')] = $estado;
    }
}
foreach (['Nuevo', 'En proceso', 'Contactado', 'Resuelto'] as $estadoBase) {
    $formContactEstados[mb_strtolower($estadoBase, 'UTF-8')] ??= $estadoBase;
}
$formContactEstados = array_values($formContactEstados);
?>
<style>
  .im-tabla-tareas__acciones {
    overflow: visible;
    position: relative;
  }

  .im-menu-tabla[data-im-menu].abierto {
    z-index: 120;
  }

  .im-menu-tabla[data-im-menu] > .im-menu-tabla__panel {
    z-index: 130;
  }

  .im-contacto-drawer {
    width: min(560px, calc(100vw - 1rem));
  }

  .im-contacto-drawer__contenido {
    display: grid;
    gap: 1rem;
  }

  .im-contacto-drawer__hero,
  .im-contacto-drawer__seccion {
    display: grid;
    gap: .75rem;
    padding: 1rem;
    border: 1px solid var(--im-color-borde);
    border-radius: var(--im-radio);
    background: var(--im-color-superficie);
  }

  .im-contacto-drawer__hero h4,
  .im-contacto-drawer__seccion h4,
  .im-contacto-drawer__hero p,
  .im-contacto-drawer__seccion p {
    margin: 0;
  }

  .im-contacto-drawer__meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: .75rem;
  }

  .im-contacto-drawer__dato {
    display: grid;
    gap: .25rem;
    padding: .85rem 1rem;
    border-radius: var(--im-radio);
    background: var(--im-color-superficie-2);
  }

  .im-contacto-drawer__dato small,
  .im-contacto-drawer__estado-ayuda {
    color: var(--im-color-texto-suave);
  }

  .im-contacto-drawer__mensaje {
    white-space: pre-wrap;
    line-height: 1.55;
  }

  .im-contacto-drawer__estado {
    display: grid;
    gap: .75rem;
  }

  .im-contacto-drawer__estado-linea {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
  }

  .im-contacto-drawer__estado-linea .im-campo {
    flex: 1 1 240px;
  }

  .im-contacto-drawer__estado-feedback[data-status="success"] {
    color: var(--im-color-exito);
  }

  .im-contacto-drawer__estado-feedback[data-status="error"] {
    color: #ba1a1a;
  }

  @media (max-width: 640px) {
    .im-contacto-drawer__meta {
      grid-template-columns: 1fr;
    }
  }
</style>

<article class="im-tabla-tareas__tarjeta">
  <div class="im-tabla-tareas__cabecera">
    <div>
      <h3>Contactos recibidos</h3>
      <p>Consultas registradas desde tu pagina web desde el formulario de contacto.</p>
    </div>
    <span class="im-chip"><?= number_format(count($formContactRows), 0, ',', '.') ?> contactos</span>
  </div>
  <?php if (!$formContactRows): ?>
    <div class="im-alerta im-alerta--info">Todavia no hay contactos registrados en su pagina web.</div>
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
            <th>Consulta</th>
            <th>Estado</th>
            <th class="im-tabla-tareas__acciones">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($formContactRows as $contacto): ?>
            <?php
            $contactoEstado = (string) ($contacto['state'] ?? '');
            $contactoConsulta = (string) ($contacto['contact_description'] ?? $contacto['contact_consultation'] ?? '');
            ?>
            <tr data-form-contact-row="<?= (int) ($contacto['id'] ?? 0) ?>">
              <td><?= htmlspecialchars($formContactFecha($contacto['created_at'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td class="im-tabla-tareas__nombre">
                <?= htmlspecialchars((string) ($contacto['project_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>
                <br><small><?= htmlspecialchars((string) ($contacto['allowed_domain'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></small>
              </td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_nombre'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_email'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contacto['contact_whatsapp'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($formContactText($contactoConsulta), ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <span class="im-chip <?= htmlspecialchars($formContactEstadoClase($contactoEstado), ENT_QUOTES, 'UTF-8') ?>" data-form-contact-state-chip>
                  <?= htmlspecialchars($formContactText($contactoEstado), ENT_QUOTES, 'UTF-8') ?>
                </span>
              </td>
              <td class="im-tabla-tareas__acciones">
                <div class="im-menu-tabla" data-im-menu data-im-menu-dinamico>
                  <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
                  <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
                    <button type="button" role="menuitem" data-ver-contacto="<?= $formContactToJson($contacto) ?>">
                      <span class="material-symbols-rounded" aria-hidden="true">visibility</span>
                      Ver contacto
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</article>

<div class="im-modal-cortina im-drawer-cortina" data-form-contact-backdrop></div>
<aside class="im-drawer im-contacto-drawer" role="dialog" aria-modal="true" aria-labelledby="form-contact-drawer-title" aria-hidden="true" data-form-contact-drawer>
  <header class="im-drawer__cabecera">
    <div>
      <p class="im-sobrelinea">Contacto recibido</p>
      <h3 id="form-contact-drawer-title">Detalle del contacto</h3>
    </div>
    <button class="im-boton-icono material-symbols-rounded" type="button" data-form-contact-close aria-label="Cerrar dialog">close</button>
  </header>
  <div class="im-drawer__contenido im-contacto-drawer__contenido" data-form-contact-content></div>
  <footer class="im-drawer__acciones">
    <button class="im-boton im-boton--tonal" type="button" data-form-contact-close>Cerrar</button>
  </footer>
</aside>

<script>
  (() => {
    const drawer = document.querySelector('[data-form-contact-drawer]');
    const backdrop = document.querySelector('[data-form-contact-backdrop]');
    const content = document.querySelector('[data-form-contact-content]');
    if (!drawer || !backdrop || !content) {
      return;
    }

    const stateOptions = <?= json_encode($formContactEstados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    let activeContact = null;
    let updatingState = false;

    const escapeHtml = (value) => String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const formatText = (value) => {
      const text = String(value ?? '').trim();
      return text !== '' ? text : '-';
    };

    const formatDate = (value) => {
      const text = String(value ?? '').trim();
      if (!text) {
        return '-';
      }

      const date = new Date(text.replace(' ', 'T'));
      if (Number.isNaN(date.getTime())) {
        return text;
      }

      return new Intl.DateTimeFormat('es-AR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      }).format(date);
    };

    const formatStateClass = (value) => {
      const state = String(value ?? '').trim().toLowerCase();
      if (['nuevo', 'nueva', 'pendiente'].includes(state)) {
        return 'im-chip--alerta';
      }
      if (['contactado', 'resuelto', 'cerrado', 'completado'].includes(state)) {
        return 'im-chip--exito';
      }
      return '';
    };

    const closeMenus = () => {
      document.querySelectorAll('[data-im-menu]').forEach((menu) => {
        menu.classList.remove('abierto');
        menu.querySelector('[data-im-menu-panel]')?.classList.remove('abierto');
        menu.querySelector('[data-im-menu-trigger]')?.setAttribute('aria-expanded', 'false');
      });
    };

    const setDrawer = (open) => {
      drawer.classList.toggle('abierto', open);
      backdrop.classList.toggle('abierto', open);
      drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    };

    const buildStateOptions = (currentState) => {
      const options = [...stateOptions];
      if (currentState && !options.includes(currentState)) {
        options.unshift(currentState);
      }

      return options.map((option) => `
        <option value="${escapeHtml(option)}" ${option === currentState ? 'selected' : ''}>${escapeHtml(option)}</option>
      `).join('');
    };

    const detailCard = (label, value) => `
      <article class="im-contacto-drawer__dato">
        <small>${escapeHtml(label)}</small>
        <strong>${value}</strong>
      </article>
    `;

    const renderDrawer = (contact) => {
      const rawState = String(contact.state ?? '').trim();
      const state = formatText(rawState);
      const consultation = formatText(contact.contact_description || contact.contact_consultation);
      content.innerHTML = `
        <section class="im-contacto-drawer__hero">
          <div>
            <span class="im-chip ${escapeHtml(formatStateClass(state))}" data-form-contact-drawer-chip>${escapeHtml(state)}</span>
          </div>
          <div>
            <h4>${escapeHtml(formatText(contact.contact_nombre || 'Contacto sin nombre'))}</h4>
            <p>${escapeHtml(formatText(contact.project_name))}</p>
          </div>
          <div class="im-contacto-drawer__meta">
            ${detailCard('Fecha', escapeHtml(formatDate(contact.created_at)))}
            ${detailCard('Dominio', escapeHtml(formatText(contact.allowed_domain)))}
            ${detailCard('Email', escapeHtml(formatText(contact.contact_email)))}
            ${detailCard('WhatsApp', escapeHtml(formatText(contact.contact_whatsapp)))}
            ${detailCard('Pagina', escapeHtml(formatText(contact.page)))}
            ${detailCard('ID contacto', escapeHtml(String(contact.id ?? '-')))}
          </div>
        </section>
        <section class="im-contacto-drawer__seccion">
          <div>
            <h4>Descripción de consulta</h4>
          </div>
          <div class="im-contacto-drawer__mensaje">${escapeHtml(consultation)}</div>
        </section>
        <section class="im-contacto-drawer__seccion im-contacto-drawer__estado">
          <div>
            <h4>Estado del contacto</h4>
            <p class="im-contacto-drawer__estado-ayuda">Cuando cambies el estado se guarda automaticamente.</p>
          </div>
          <div class="im-contacto-drawer__estado-linea">
            <label class="im-campo im-campo-material" data-im-campo="generico">
              <span>Estado</span>
              <select data-form-contact-state-select>
                ${buildStateOptions(rawState || stateOptions[0] || 'Nuevo')}
              </select>
              <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">flag</i>
            </label>
            <span class="im-contacto-drawer__estado-feedback" data-form-contact-state-feedback></span>
          </div>
        </section>
      `;
    };

    const updateTableState = (contactId, state) => {
      const row = document.querySelector(`[data-form-contact-row="${String(contactId)}"]`);
      const chip = row?.querySelector('[data-form-contact-state-chip]');
      if (!chip) {
        return;
      }

      chip.textContent = formatText(state);
      chip.className = `im-chip ${formatStateClass(state)}`.trim();
    };

    const updateDrawerState = (state) => {
      const chip = content.querySelector('[data-form-contact-drawer-chip]');
      if (!chip) {
        return;
      }

      chip.textContent = formatText(state);
      chip.className = `im-chip ${formatStateClass(state)}`.trim();
    };

    const setFeedback = (message, status = '') => {
      const feedback = content.querySelector('[data-form-contact-state-feedback]');
      if (!feedback) {
        return;
      }

      feedback.textContent = message;
      if (status) {
        feedback.setAttribute('data-status', status);
      } else {
        feedback.removeAttribute('data-status');
      }
    };

    const saveState = async (contactId, state) => {
      const body = new URLSearchParams({
        contact_id: String(contactId),
        state: state,
      });

      const response = await fetch(`${window.location.pathname}?ajax=actualizar_contacto_estado`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
          'Accept': 'application/json',
        },
        body: body.toString(),
      });

      let payload = null;
      try {
        payload = await response.json();
      } catch (error) {
        payload = null;
      }

      if (!response.ok || !payload?.ok) {
        throw new Error(payload?.message || 'No se pudo guardar el estado del contacto.');
      }

      return payload;
    };

    document.addEventListener('click', (event) => {
      const menuTrigger = event.target.closest('[data-im-menu-dinamico] [data-im-menu-trigger]');
      if (menuTrigger) {
        event.stopPropagation();
        const menu = menuTrigger.closest('[data-im-menu]');
        const panel = menu?.querySelector('[data-im-menu-panel]');
        const open = !panel?.classList.contains('abierto');
        closeMenus();
        menu?.classList.toggle('abierto', open);
        panel?.classList.toggle('abierto', open);
        menuTrigger.setAttribute('aria-expanded', String(open));
        return;
      }

      const detailTrigger = event.target.closest('[data-ver-contacto]');
      if (detailTrigger) {
        closeMenus();
        try {
          activeContact = JSON.parse(detailTrigger.getAttribute('data-ver-contacto') || '{}');
          renderDrawer(activeContact);
          setDrawer(true);
        } catch (error) {
          activeContact = null;
        }
        return;
      }

      if (event.target.closest('[data-form-contact-close], [data-form-contact-backdrop]')) {
        activeContact = null;
        setDrawer(false);
      }

      if (!event.target.closest('[data-im-menu-dinamico]')) {
        closeMenus();
      }
    });

    document.addEventListener('change', async (event) => {
      const select = event.target.closest('[data-form-contact-state-select]');
      if (!select || !activeContact || updatingState) {
        return;
      }

      const nextState = String(select.value || '').trim();
      const previousState = String(activeContact.state || '').trim() || formatText(activeContact.state);
      if (!nextState || nextState === previousState) {
        return;
      }

      updatingState = true;
      select.disabled = true;
      setFeedback('Guardando...', '');

      try {
        await saveState(Number(activeContact.id || 0), nextState);
        activeContact.state = nextState;
        updateTableState(activeContact.id, nextState);
        updateDrawerState(nextState);
        setFeedback('Estado actualizado.', 'success');
      } catch (error) {
        select.value = previousState;
        setFeedback(error instanceof Error ? error.message : 'No se pudo actualizar el estado.', 'error');
      } finally {
        select.disabled = false;
        updatingState = false;
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeMenus();
        activeContact = null;
        setDrawer(false);
      }
    });
  })();
</script>
