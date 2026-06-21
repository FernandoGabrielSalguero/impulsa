<?php
$emprendedorCimientosId = $emprendedorCimientosId ?? 'emprendedor-cimientos';
?>
<style>
  .im-cimientos-drawer {
    width: min(540px, calc(100vw - 1rem));
  }

  .im-cimientos-drawer__estado,
  .im-cimientos-drawer__hero,
  .im-cimientos-drawer__grid {
    display: grid;
    gap: 1rem;
  }

  .im-cimientos-drawer__estado {
    min-height: 240px;
    place-items: center;
    text-align: center;
    color: var(--im-color-texto-suave);
  }

  .im-cimientos-drawer__estado .material-symbols-rounded {
    font-size: 2rem;
  }

  .im-cimientos-drawer__hero {
    padding: 1rem;
    border: 1px solid var(--im-color-borde);
    border-radius: var(--im-radio);
    background: var(--im-color-superficie-2);
  }

  .im-cimientos-drawer__hero-copy p,
  .im-cimientos-drawer__card-head p,
  .im-cimientos-drawer__placeholder,
  .im-cimientos-drawer__contenido-texto p {
    margin: 0;
    color: var(--im-color-texto-suave);
  }

  .im-cimientos-drawer__card {
    display: grid;
    gap: .9rem;
    padding: 1rem;
    border: 1px solid var(--im-color-borde);
    border-radius: var(--im-radio);
    background: var(--im-color-superficie);
  }

  .im-cimientos-drawer__card-head {
    display: flex;
    gap: .75rem;
    justify-content: space-between;
    align-items: start;
  }

  .im-cimientos-drawer__card-head h4,
  .im-cimientos-drawer__contenido-texto strong {
    margin: 0;
    color: var(--im-color-texto);
  }

  .im-cimientos-drawer__contenido-texto {
    display: grid;
    gap: .5rem;
    padding: .9rem 1rem;
    border-radius: calc(var(--im-radio) - 4px);
    background: color-mix(in srgb, var(--im-color-principal) 6%, var(--im-color-superficie));
  }

  .im-cimientos-drawer__contenido-texto p {
    white-space: pre-wrap;
  }

  @media (max-width: 640px) {
    .im-cimientos-drawer__card-head {
      flex-direction: column;
    }
  }
</style>
<div class="im-modal-cortina im-drawer-cortina" data-cimientos-backdrop="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>"></div>
<aside class="im-drawer im-cimientos-drawer" role="dialog" aria-modal="true" aria-labelledby="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>-titulo" aria-hidden="true" data-cimientos-drawer="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>">
  <header class="im-drawer__cabecera">
    <div>
      <p class="im-sobrelinea">Cimientos del emprendedor</p>
      <h3 id="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>-titulo">Ver cimientos</h3>
    </div>
    <button class="im-boton-icono material-symbols-rounded" type="button" data-cerrar-cimientos="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>" aria-label="Cerrar dialog">close</button>
  </header>
  <div class="im-drawer__contenido" data-cimientos-contenido="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>">
    <article class="im-cimientos-drawer__estado">
      <div class="im-spinner" aria-hidden="true"></div>
      <p>Selecciona un emprendedor para cargar sus cimientos.</p>
    </article>
  </div>
  <footer class="im-drawer__acciones">
    <button class="im-boton im-boton--tonal" type="button" data-cerrar-cimientos="<?= htmlspecialchars($emprendedorCimientosId, ENT_QUOTES, 'UTF-8') ?>">Cerrar</button>
  </footer>
</aside>

<script>
  (() => {
    const drawerId = <?= json_encode($emprendedorCimientosId, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const drawer = document.querySelector(`[data-cimientos-drawer="${drawerId}"]`);
    const backdrop = document.querySelector(`[data-cimientos-backdrop="${drawerId}"]`);
    const content = document.querySelector(`[data-cimientos-contenido="${drawerId}"]`);
    if (!drawer || !backdrop || !content) {
      return;
    }

    const escapeHtml = (value) => String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const closeDrawer = () => {
      drawer.classList.remove('abierto');
      drawer.setAttribute('aria-hidden', 'true');
      backdrop.classList.remove('abierto');
    };

    const openDrawer = () => {
      drawer.classList.add('abierto');
      drawer.setAttribute('aria-hidden', 'false');
      backdrop.classList.add('abierto');
    };

    const estadoChip = (item) => `<span class="im-chip ${escapeHtml(item.estado_clase || 'im-chip')}">${escapeHtml(item.estado_label || 'Pendiente')}</span>`;

    const renderModulo = (item) => {
      const contenido = Number(item.completado || 0) === 1 && String(item.contenido || '').trim() !== ''
        ? `<div class="im-cimientos-drawer__contenido-texto"><strong>Contenido final</strong><p>${escapeHtml(item.contenido)}</p></div>`
        : `<p class="im-cimientos-drawer__placeholder">Todavia no hay una version final disponible para este cimiento.</p>`;

      return `
        <article class="im-cimientos-drawer__card">
          <div class="im-cimientos-drawer__card-head">
            <div>
              <h4>${escapeHtml(item.titulo || '')}</h4>
              <p>${escapeHtml(item.descripcion || '')}</p>
            </div>
            ${estadoChip(item)}
          </div>
          ${contenido}
        </article>
      `;
    };

    const renderDrawer = (payload) => {
      const usuario = payload.usuario || {};
      const cimientos = payload.cimientos || {};
      const modulos = [cimientos.mision, cimientos.vision, cimientos.buyer_persona].filter(Boolean);
      const emprendimiento = String(usuario.nombre_emprendimiento || '').trim();

      content.innerHTML = `
        <article class="im-cimientos-drawer__hero">
          <div class="im-cimientos-drawer__hero-copy">
            <div class="im-chip-lista">
              <span class="im-chip">${escapeHtml(usuario.nombre_visible || 'Usuario')}</span>
              ${emprendimiento ? `<span class="im-chip im-chip--tonal">${escapeHtml(emprendimiento)}</span>` : ''}
            </div>
            <p>${escapeHtml(usuario.correo || '')}</p>
          </div>
        </article>
        <section class="im-cimientos-drawer__grid">
          ${modulos.map(renderModulo).join('')}
        </section>
      `;
    };

    const renderLoading = () => {
      content.innerHTML = `
        <article class="im-cimientos-drawer__estado">
          <div class="im-spinner" aria-hidden="true"></div>
          <p>Cargando cimientos del emprendedor...</p>
        </article>
      `;
    };

    const renderError = (message) => {
      content.innerHTML = `
        <article class="im-cimientos-drawer__estado">
          <span class="material-symbols-rounded" aria-hidden="true">error</span>
          <p>${escapeHtml(message || 'No pudimos cargar los cimientos en este momento.')}</p>
        </article>
      `;
    };

    document.addEventListener('click', async (event) => {
      const trigger = event.target.closest('[data-ver-cimientos]');
      if (trigger) {
        const userId = Number(trigger.getAttribute('data-ver-cimientos') || '0');
        if (!userId) {
          return;
        }

        openDrawer();
        renderLoading();

        try {
          const params = new URLSearchParams({
            ajax: 'cimientos_usuario',
            user_id: String(userId),
          });
          const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
            headers: { Accept: 'application/json' },
          });
          const data = await response.json();
          if (!response.ok || !data.ok) {
            renderError(data.mensaje || 'No encontramos informacion para este emprendedor.');
            return;
          }

          renderDrawer(data.data || {});
        } catch (error) {
          renderError('No pudimos cargar los cimientos en este momento.');
        }
      }

      if (event.target.closest(`[data-cerrar-cimientos="${drawerId}"], [data-cimientos-backdrop="${drawerId}"]`)) {
        closeDrawer();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeDrawer();
      }
    });
  })();
</script>
