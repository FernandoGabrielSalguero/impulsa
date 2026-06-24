<?php

declare(strict_types=1);

$h = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$apiBlogFlash = $apiBlogFlash ?? null;
$apiBlogIntegraciones = $apiBlogIntegraciones ?? [];
$apiBlogItems = $apiBlogItems ?? [];
$apiBlogEditingItem = $apiBlogEditingItem ?? null;
$apiBlogSelectedIntegration = $apiBlogSelectedIntegration ?? null;
$apiBlogCurrent = is_array($apiBlogEditingItem) ? $apiBlogEditingItem : [];
$apiBlogPublicationInput = '';

if (!empty($apiBlogCurrent['publication_date'])) {
    $apiBlogPublicationInput = date('Y-m-d\TH:i', strtotime((string) $apiBlogCurrent['publication_date']));
}

$apiBlogStatusLabels = [
    'draft' => ['Borrador', 'im-chip--pendiente'],
    'active' => ['Activo', 'im-chip--completado'],
    'inactive' => ['Inactivo', 'im-chip--alerta'],
];
?>
<style>
  .im-blog-toolbar {
    display: flex;
    gap: 1rem;
    align-items: end;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .im-blog-acciones {
    display: flex;
    align-items: center;
    gap: .5rem;
  }

  .im-blog-accion--editar {
    color: var(--im-color-principal, #112c4e);
  }

  .im-blog-accion--eliminar {
    color: #ba1a1a;
  }

  .im-blog-modal .im-dialog__contenido {
    min-height: 0;
    overflow: auto;
    display: grid;
    gap: 1rem;
    max-height: min(70vh, 760px);
  }

  .im-blog-modal .im-dialog__acciones {
    display: flex;
    gap: .75rem;
    justify-content: flex-end;
    flex-wrap: wrap;
  }

  .im-blog-tabla-nombre strong,
  .im-blog-tabla-nombre small {
    display: block;
  }

  .im-blog-modal {
    max-height: calc(100vh - 2rem);
  }
</style>

<section class="im-seccion-documento activa" id="api-blog-builder" data-panel="api-blog-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea"><?= $h($apiBlogRoleLabel) ?></p>
      <h2><?= $h($apiBlogPageTitle) ?></h2>
      <?php if ($apiBlogPageDescription !== ''): ?>
        <p><?= $h($apiBlogPageDescription) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <?php if (is_array($apiBlogFlash) && trim((string) ($apiBlogFlash['mensaje'] ?? '')) !== ''): ?>
    <div class="im-alerta <?= ($apiBlogFlash['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
      <?= $h($apiBlogFlash['mensaje'] ?? '') ?>
    </div>
  <?php endif; ?>

  <article class="im-tarjeta">
    <div class="im-tarjeta__cabecera">
      <div>
        <h3>Mis publicaciones</h3>
        <p>Administra tus blogs creados.</p>
      </div>
    </div>

    <div class="im-blog-toolbar">
      <form method="get" class="im-formulario">
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Integracion asociada</span>
          <select name="integration_id" onchange="this.form.submit()">
            <?php foreach ($apiBlogIntegraciones as $integration): ?>
              <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($apiBlogSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
      </form>

      <?php if ($apiBlogIntegraciones): ?>
        <?php if ($apiBlogCurrent): ?>
          <a class="im-boton im-boton--principal" href="?integration_id=<?= (int) ($apiBlogSelectedIntegration['id'] ?? 0) ?>">
            Nueva publicacion
          </a>
        <?php else: ?>
          <button class="im-boton im-boton--principal" type="button" data-blog-open-modal="create">
            Nueva publicacion
          </button>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <?php if (!$apiBlogIntegraciones): ?>
      <div class="im-alerta im-alerta--info">Todavia no hay integraciones accesibles para tu cuenta.</div>
    <?php elseif (!$apiBlogItems): ?>
      <div class="im-alerta im-alerta--info">Aun no hay publicaciones para esta integracion.</div>
    <?php else: ?>
      <div class="im-tabla-contenedor">
        <table class="im-tabla">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Estado</th>
              <th>Fecha de publicacion</th>
              <th>Orden</th>
              <th>Tiene foto</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($apiBlogItems as $item): ?>
              <?php
              $statusKey = (string) ($item['status'] ?? 'draft');
              $statusMeta = $apiBlogStatusLabels[$statusKey] ?? [$statusKey, 'im-chip--alerta'];
              $tieneFoto = !empty($item['cover_image_path']);
              ?>
              <tr>
                <td class="im-blog-tabla-nombre">
                  <strong><?= $h($item['title'] ?? '') ?></strong>
                  <?php if (!empty($item['slug'])): ?>
                    <small><?= $h($item['slug']) ?></small>
                  <?php endif; ?>
                </td>
                <td><span class="im-chip <?= $h($statusMeta[1]) ?>"><?= $h($statusMeta[0]) ?></span></td>
                <td><?= !empty($item['publication_date']) ? $h(date('d/m/Y H:i', strtotime((string) $item['publication_date']))) : '-' ?></td>
                <td><?= (int) ($item['sort_order'] ?? 1) ?></td>
                <td>
                  <span class="im-chip <?= $tieneFoto ? 'im-chip--completado' : 'im-chip--pendiente' ?>">
                    <?= $tieneFoto ? 'Si' : 'No' ?>
                  </span>
                </td>
                <td>
                  <div class="im-blog-acciones">
                    <a
                      class="im-boton-icono material-symbols-rounded im-blog-accion--editar"
                      href="?integration_id=<?= (int) ($apiBlogSelectedIntegration['id'] ?? 0) ?>&edit_id=<?= (int) ($item['id'] ?? 0) ?>"
                      aria-label="Editar publicacion"
                    >edit</a>
                    <button
                      class="im-boton-icono material-symbols-rounded im-blog-accion--eliminar"
                      type="button"
                      data-blog-delete-open
                      data-blog-delete-id="<?= (int) ($item['id'] ?? 0) ?>"
                      data-blog-delete-title="<?= $h($item['title'] ?? 'Publicacion seleccionada') ?>"
                      aria-label="Eliminar publicacion"
                    >delete</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </article>
</section>

<div class="im-modal-cortina" data-blog-close-modal></div>
<section class="im-dialog im-blog-modal" role="dialog" aria-modal="true" aria-labelledby="api-blog-modal-titulo" aria-hidden="true" data-blog-modal>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea">Blog</p>
      <h3 id="api-blog-modal-titulo"><?= $apiBlogCurrent ? 'Editar publicacion' : 'Nueva publicacion' ?></h3>
    </div>
    <button class="im-boton-icono" type="button" data-blog-close-modal aria-label="Cerrar dialog"></button>
  </header>

  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
    <input type="hidden" name="item_id" value="<?= (int) ($apiBlogCurrent['id'] ?? 0) ?>">

    <div class="im-dialog__contenido">
      <div class="im-formulario">
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Integracion asociada</span>
          <select name="api_integration_id" required>
            <?php foreach ($apiBlogIntegraciones as $integration): ?>
              <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($apiBlogCurrent['api_integration_id'] ?? ($apiBlogSelectedIntegration['id'] ?? 0)) ? 'selected' : '' ?>>
                <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Titulo</span>
          <input type="text" name="title" maxlength="180" value="<?= $h($apiBlogCurrent['title'] ?? '') ?>" required>
        </label>
        <label class="im-campo im-campo-material">
          <span>Subtitulo</span>
          <input type="text" name="subtitle" maxlength="255" value="<?= $h($apiBlogCurrent['subtitle'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Autor</span>
          <input type="text" name="author" maxlength="180" value="<?= $h($apiBlogCurrent['author'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Estado</span>
          <select name="status">
            <option value="draft" <?= ($apiBlogCurrent['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option>
            <option value="active" <?= ($apiBlogCurrent['status'] ?? '') === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= ($apiBlogCurrent['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Fecha de publicacion</span>
          <input type="datetime-local" name="publication_date" value="<?= $h($apiBlogPublicationInput) ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Categoria</span>
          <input type="text" name="category" maxlength="120" value="<?= $h($apiBlogCurrent['category'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Subcategoria</span>
          <input type="text" name="subcategory" maxlength="120" value="<?= $h($apiBlogCurrent['subcategory'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Orden</span>
          <input type="number" name="sort_order" min="1" value="<?= (int) ($apiBlogCurrent['sort_order'] ?? 1) ?>">
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Resumen</span>
          <textarea name="excerpt" rows="3" maxlength="300"><?= $h($apiBlogCurrent['excerpt'] ?? '') ?></textarea>
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Contenido HTML</span>
          <textarea name="description_html" rows="10" required><?= $h($apiBlogCurrent['description_html'] ?? '') ?></textarea>
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Bibliografia</span>
          <textarea name="bibliography" rows="4"><?= $h($apiBlogCurrent['bibliography'] ?? '') ?></textarea>
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Metadata JSON</span>
          <textarea name="metadata_json" rows="3"><?= $h($apiBlogCurrent['metadata_json'] ?? '') ?></textarea>
        </label>

        <label class="im-campo im-campo-material">
          <span>Imagen de portada</span>
          <input type="file" name="cover_image_file" accept=".jpg,.jpeg,.png,.webp">
        </label>
        <label class="im-campo im-campo-material">
          <span>Adjunto opcional</span>
          <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.txt">
        </label>

        <?php if (!empty($apiBlogCurrent['cover_image_path_url']) || !empty($apiBlogCurrent['attachment_path_url'])): ?>
          <div class="im-chip-lista im-campo--ancho">
            <?php if (!empty($apiBlogCurrent['cover_image_path_url'])): ?>
              <a class="im-chip" href="<?= $h($apiBlogCurrent['cover_image_path_url']) ?>" target="_blank" rel="noreferrer">Ver portada</a>
              <label class="im-chip">
                <input type="checkbox" name="remove_cover_image" value="1"> Quitar portada
              </label>
            <?php endif; ?>
            <?php if (!empty($apiBlogCurrent['attachment_path_url'])): ?>
              <a class="im-chip" href="<?= $h($apiBlogCurrent['attachment_path_url']) ?>" target="_blank" rel="noreferrer">Ver adjunto</a>
              <label class="im-chip">
                <input type="checkbox" name="remove_attachment" value="1"> Quitar adjunto
              </label>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-blog-close-modal>Cancelar</button>
      <button class="im-boton im-boton--principal" type="submit" name="api_blog_submit" value="save">Guardar publicacion</button>
    </footer>
  </form>
</section>

<div class="im-modal-cortina" data-blog-close-delete></div>
<section class="im-dialog im-blog-modal" role="dialog" aria-modal="true" aria-labelledby="api-blog-delete-titulo" aria-hidden="true" data-blog-delete-modal>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea">Accion irreversible</p>
      <h3 id="api-blog-delete-titulo">Eliminar publicacion</h3>
    </div>
    <button class="im-boton-icono" type="button" data-blog-close-delete aria-label="Cerrar dialog"></button>
  </header>

  <form method="post">
    <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
    <input type="hidden" name="item_id" value="" data-blog-delete-id-input>

    <div class="im-dialog__contenido">
      <p>Estas por eliminar la publicacion:</p>
      <p><strong data-blog-delete-title>Publicacion seleccionada</strong></p>
      <p>Esta accion borrara el registro y sus archivos asociados. No se puede deshacer.</p>
    </div>

    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-blog-close-delete>Cancelar</button>
      <button class="im-boton im-boton--principal im-blog-accion--eliminar" type="submit" name="api_blog_submit" value="delete">Confirmar eliminacion</button>
    </footer>
  </form>
</section>

<script>
  (() => {
    const modal = document.querySelector('[data-blog-modal]');
    const cortina = document.querySelector('[data-blog-close-modal].im-modal-cortina');
    if (!modal || !cortina) {
      return;
    }

    const alternarModal = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    document.addEventListener('click', (evento) => {
      if (evento.target.closest('[data-blog-open-modal]')) {
        alternarModal(true);
        return;
      }

      if (evento.target.closest('[data-blog-close-modal]')) {
        alternarModal(false);
      }
    });

    document.addEventListener('keydown', (evento) => {
      if (evento.key === 'Escape') {
        alternarModal(false);
      }
    });

    <?php if ($apiBlogCurrent): ?>
    alternarModal(true);
    <?php endif; ?>
  })();

  (() => {
    const modal = document.querySelector('[data-blog-delete-modal]');
    const cortina = document.querySelector('[data-blog-close-delete].im-modal-cortina');
    const inputId = document.querySelector('[data-blog-delete-id-input]');
    const titulo = document.querySelector('[data-blog-delete-title]');
    if (!modal || !cortina || !inputId || !titulo) {
      return;
    }

    const alternarModal = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    document.addEventListener('click', (evento) => {
      const trigger = evento.target.closest('[data-blog-delete-open]');
      if (trigger) {
        inputId.value = trigger.getAttribute('data-blog-delete-id') || '';
        titulo.textContent = trigger.getAttribute('data-blog-delete-title') || 'Publicacion seleccionada';
        alternarModal(true);
        return;
      }

      if (evento.target.closest('[data-blog-close-delete]')) {
        alternarModal(false);
      }
    });

    document.addEventListener('keydown', (evento) => {
      if (evento.key === 'Escape') {
        alternarModal(false);
      }
    });
  })();
</script>
