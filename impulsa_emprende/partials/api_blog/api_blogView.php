<?php

declare(strict_types=1);

$h = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$apiBlogFlash = $apiBlogFlash ?? null;
$apiBlogIntegraciones = $apiBlogIntegraciones ?? [];
$apiBlogItems = $apiBlogItems ?? [];
$apiBlogEditingItem = $apiBlogEditingItem ?? null;
$apiBlogSelectedIntegration = $apiBlogSelectedIntegration ?? null;
$apiBlogCurrent = is_array($apiBlogEditingItem) ? $apiBlogEditingItem : [];
$apiBlogDescriptionValue = (string) ($apiBlogCurrent['description_html'] ?? '<p></p>');
$apiBlogBaseQuery = '?integration_id=' . (int) ($apiBlogSelectedIntegration['id'] ?? 0);
$apiBlogShouldOpenDialog = $apiBlogCurrent !== [] || (is_array($apiBlogFlash) && ($apiBlogFlash['estado'] ?? '') === 'error');
?>
<section class="im-seccion-documento activa" id="api-blog-builder" data-panel="api-blog-builder">
  <style>
    .im-blog-header-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      align-items: center;
      justify-content: flex-end;
    }

    .im-blog-grid {
      display: grid;
      gap: 1rem;
    }

    .im-blog-card {
      border: 1px solid rgba(15, 23, 42, .08);
      border-radius: 24px;
      background: linear-gradient(180deg, rgba(255, 255, 255, .98) 0%, rgba(248, 250, 252, .94) 100%);
      padding: 1.25rem;
      box-shadow: 0 18px 40px rgba(15, 23, 42, .08);
    }

    .im-blog-card__head,
    .im-blog-card__meta,
    .im-blog-card__actions {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      align-items: center;
      justify-content: space-between;
    }

    .im-blog-card__head h4,
    .im-blog-card__head p,
    .im-blog-card__content > :first-child {
      margin-top: 0;
    }

    .im-blog-card__head p,
    .im-blog-card__content,
    .im-blog-card__empty {
      color: rgba(15, 23, 42, .78);
    }

    .im-blog-card__meta {
      justify-content: flex-start;
      margin: 1rem 0;
    }

    .im-blog-card__content {
      border-top: 1px solid rgba(15, 23, 42, .08);
      margin-top: 1rem;
      padding-top: 1rem;
      line-height: 1.65;
      overflow-wrap: anywhere;
    }

    .im-blog-card__content img {
      max-width: 100%;
      height: auto;
      border-radius: 16px;
    }

    .im-blog-card__empty {
      margin: 1rem 0 0;
      font-style: italic;
    }

    .im-blog-dialog {
      width: min(960px, calc(100vw - 2rem));
      height: min(92vh, 980px);
      max-height: calc(100vh - 2rem);
      border: none;
      border-radius: 28px;
      padding: 0;
      overflow: hidden;
      box-shadow: 0 32px 64px rgba(15, 23, 42, .22);
    }

    .im-blog-dialog::backdrop {
      background: rgba(15, 23, 42, .55);
      backdrop-filter: blur(4px);
    }

    .im-blog-dialog__shell {
      display: grid;
      grid-template-rows: auto 1fr;
      height: 100%;
      background: #fff;
    }

    .im-blog-dialog__header {
      display: flex;
      gap: 1rem;
      align-items: flex-start;
      justify-content: space-between;
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      background: linear-gradient(135deg, rgba(241, 245, 249, .95) 0%, rgba(255, 255, 255, .98) 100%);
    }

    .im-blog-dialog__header h3,
    .im-blog-dialog__header p {
      margin: 0;
    }

    .im-blog-dialog__body {
      min-height: 0;
      padding: 1.5rem;
      overflow-y: auto;
      overscroll-behavior: contain;
    }

    .im-blog-form-layout {
      display: grid;
      gap: 1rem;
    }

    .im-blog-form-layout .im-formulario__acciones {
      position: sticky;
      bottom: -1.5rem;
      z-index: 2;
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      justify-content: flex-end;
      padding: 1rem 0 0;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, .92) 18%, rgba(255, 255, 255, 1) 100%);
    }

    .im-blog-editor {
      min-height: 320px;
      border-radius: 20px;
      border: 1px solid rgba(15, 23, 42, .12);
      background: #fff;
      overflow: hidden;
    }

    .im-blog-editor .ql-toolbar {
      border: none;
      border-bottom: 1px solid rgba(15, 23, 42, .08);
      background: rgba(248, 250, 252, .96);
      padding: .8rem .9rem;
    }

    .im-blog-editor .ql-container {
      border: none;
      font-family: inherit;
    }

    .im-blog-editor .ql-picker,
    .im-blog-editor .ql-stroke {
      color: #334155;
      stroke: #334155;
    }

    .im-blog-editor .ql-fill {
      fill: #334155;
    }

    .im-blog-editor .ql-editor {
      min-height: 250px;
      padding: 1rem;
    }

    .im-blog-editor .ql-editor.ql-blank::before {
      left: 0;
      right: 0;
      color: rgba(15, 23, 42, .45);
      font-style: normal;
    }

    .im-blog-form-help {
      margin: .35rem 0 0;
      color: rgba(15, 23, 42, .62);
      font-size: .92rem;
    }

    @media (max-width: 720px) {
      .im-blog-dialog {
        width: calc(100vw - 1rem);
        height: calc(100vh - .5rem);
        max-height: calc(100vh - .5rem);
        border-radius: 24px 24px 0 0;
        margin-top: auto;
      }

      .im-blog-dialog__header,
      .im-blog-dialog__body {
        padding: 1rem;
      }

      .im-blog-form-layout .im-formulario__acciones {
        bottom: -1rem;
      }
    }
  </style>

  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea"><?= $h($apiBlogRoleLabel) ?></p>
      <h2><?= $h($apiBlogPageTitle) ?></h2>
      <?php if ($apiBlogPageDescription !== ''): ?>
        <p><?= $h($apiBlogPageDescription) ?></p>
      <?php endif; ?>
    </div>
    <div class="im-blog-header-actions">
      <button
        class="im-boton im-boton--principal"
        type="button"
        data-blog-dialog-open
        <?= $apiBlogIntegraciones ? '' : 'disabled' ?>
      >
        Nuevo Blog
      </button>
    </div>
  </div>

  <?php if (is_array($apiBlogFlash) && trim((string) ($apiBlogFlash['mensaje'] ?? '')) !== ''): ?>
    <div class="im-alerta <?= ($apiBlogFlash['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
      <?= $h($apiBlogFlash['mensaje'] ?? '') ?>
    </div>
  <?php endif; ?>

  <div class="im-grilla">
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Listado de publicaciones</h3>
          <p>Visualiza el contenido completo y edita cada publicacion desde el panel lateral.</p>
        </div>
      </div>

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

      <?php if (!$apiBlogIntegraciones): ?>
        <div class="im-alerta im-alerta--info">Todavia no hay integraciones accesibles para tu cuenta.</div>
      <?php elseif (!$apiBlogItems): ?>
        <div class="im-alerta im-alerta--info">Aun no hay publicaciones para esta integracion.</div>
      <?php else: ?>
        <div class="im-blog-grid">
          <?php foreach ($apiBlogItems as $item): ?>
            <?php
            $itemStatus = (string) ($item['status'] ?? 'draft');
            $itemStatusClass = $itemStatus === 'active'
                ? 'im-chip--completado'
                : ($itemStatus === 'draft' ? 'im-chip--pendiente' : 'im-chip--alerta');
            $itemContent = trim((string) ($item['description_html'] ?? ''));
            ?>
            <article class="im-blog-card">
              <div class="im-blog-card__head">
                <div>
                  <h4><?= $h($item['title'] ?? '') ?></h4>
                  <?php if (trim((string) ($item['subtitle'] ?? '')) !== ''): ?>
                    <p><?= $h($item['subtitle'] ?? '') ?></p>
                  <?php endif; ?>
                </div>
                <span class="im-chip <?= $itemStatusClass ?>"><?= $h($itemStatus) ?></span>
              </div>

              <div class="im-blog-card__meta im-chip-lista">
                <?php if (trim((string) ($item['category'] ?? '')) !== ''): ?>
                  <span class="im-chip"><?= $h($item['category'] ?? '') ?></span>
                <?php endif; ?>
                <?php if (trim((string) ($item['subcategory'] ?? '')) !== ''): ?>
                  <span class="im-chip"><?= $h($item['subcategory'] ?? '') ?></span>
                <?php endif; ?>
                <?php if (trim((string) ($item['author'] ?? '')) !== ''): ?>
                  <span class="im-chip">Autor: <?= $h($item['author'] ?? '') ?></span>
                <?php endif; ?>
                <span class="im-chip">Publicacion: <?= $h($item['publication_date'] ?? '-') ?></span>
              </div>

              <div class="im-blog-card__actions">
                <div class="im-chip-lista">
                  <a class="im-boton im-boton--texto" href="<?= $h($apiBlogBaseQuery . '&edit_id=' . (int) ($item['id'] ?? 0)) ?>">Editar</a>
                  <?php if (!empty($item['cover_image_path_url'])): ?>
                    <a class="im-boton im-boton--texto" href="<?= $h($item['cover_image_path_url']) ?>" target="_blank" rel="noreferrer">Ver portada</a>
                  <?php endif; ?>
                  <?php if (!empty($item['attachment_path_url'])): ?>
                    <a class="im-boton im-boton--texto" href="<?= $h($item['attachment_path_url']) ?>" target="_blank" rel="noreferrer">Ver adjunto</a>
                  <?php endif; ?>
                </div>
                <form method="post">
                  <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
                  <input type="hidden" name="item_id" value="<?= (int) ($item['id'] ?? 0) ?>">
                  <input type="hidden" name="target_status" value="<?= $itemStatus === 'active' ? 'inactive' : 'active' ?>">
                  <button class="im-boton im-boton--texto" type="submit" name="api_blog_submit" value="toggle_status">
                    <?= $itemStatus === 'active' ? 'Desactivar' : 'Activar' ?>
                  </button>
                </form>
              </div>

              <?php if ($itemContent !== ''): ?>
                <div class="im-blog-card__content"><?= $itemContent ?></div>
              <?php else: ?>
                <p class="im-blog-card__empty">Esta publicacion todavia no tiene contenido enriquecido.</p>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>
  </div>

  <dialog
    class="im-blog-dialog"
    data-blog-dialog
    <?= $apiBlogShouldOpenDialog ? 'data-auto-open="true"' : '' ?>
  >
    <div class="im-blog-dialog__shell">
      <div class="im-blog-dialog__header">
        <div>
          <h3><?= $apiBlogCurrent ? 'Editar publicacion' : 'Nueva publicacion' ?></h3>
          <p>Completa los campos del blog y guarda la publicacion cuando el contenido este listo.</p>
        </div>
        <button class="im-boton im-boton--texto" type="button" data-blog-dialog-close>Cerrar</button>
      </div>

      <div class="im-blog-dialog__body">
        <form method="post" enctype="multipart/form-data" class="im-formulario im-blog-form-layout" data-quill-form>
          <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
          <input type="hidden" name="item_id" value="<?= (int) ($apiBlogCurrent['id'] ?? 0) ?>">
          <input type="hidden" name="slug" value="<?= $h($apiBlogCurrent['slug'] ?? '') ?>">
          <input type="hidden" name="description_html" value="<?= $h($apiBlogDescriptionValue) ?>" data-quill-hidden>
          <input type="hidden" name="excerpt" value="<?= $h($apiBlogCurrent['excerpt'] ?? '') ?>" data-blog-excerpt-hidden>

          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Integracion asociada</span>
            <select name="api_integration_id" required>
              <?php foreach ($apiBlogIntegraciones as $integration): ?>
                <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) (($apiBlogCurrent['api_integration_id'] ?? $apiBlogSelectedIntegration['id'] ?? 0)) ? 'selected' : '' ?>>
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
            <span>Categoria</span>
            <input type="text" name="category" maxlength="120" value="<?= $h($apiBlogCurrent['category'] ?? '') ?>">
          </label>
          <label class="im-campo im-campo-material">
            <span>Subcategoria</span>
            <input type="text" name="subcategory" maxlength="120" value="<?= $h($apiBlogCurrent['subcategory'] ?? '') ?>">
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
            <input type="datetime-local" name="publication_date" value="<?= isset($apiBlogCurrent['publication_date']) && $apiBlogCurrent['publication_date'] ? $h(date('Y-m-d\TH:i', strtotime((string) $apiBlogCurrent['publication_date']))) : '' ?>">
          </label>
          <label class="im-campo im-campo-material">
            <span>Orden</span>
            <input type="number" name="sort_order" min="1" value="<?= (int) ($apiBlogCurrent['sort_order'] ?? 1) ?>">
          </label>
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Bibliografia</span>
            <textarea name="bibliography" rows="3"><?= $h($apiBlogCurrent['bibliography'] ?? '') ?></textarea>
          </label>

          <div class="im-campo im-campo--ancho">
            <span>Contenido de la publicacion</span>
            <div class="im-blog-editor im-muestra" data-quill-editor data-placeholder="Escribe aqui el cuerpo completo de la publicacion..."><?= $apiBlogDescriptionValue ?></div>
            <p class="im-blog-form-help">El extracto corto se genera automaticamente a partir del contenido.</p>
          </div>

          <label class="im-campo im-campo-material">
            <span>Portada</span>
            <input type="file" name="cover_image_file" accept=".jpg,.jpeg,.png,.webp">
          </label>
          <label class="im-campo im-campo-material">
            <span>Adjunto</span>
            <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.zip">
          </label>

          <?php if (!empty($apiBlogCurrent['cover_image_path_url']) || !empty($apiBlogCurrent['attachment_path_url'])): ?>
            <div class="im-chip-lista im-campo--ancho">
              <?php if (!empty($apiBlogCurrent['cover_image_path_url'])): ?>
                <a class="im-chip" href="<?= $h($apiBlogCurrent['cover_image_path_url']) ?>" target="_blank" rel="noreferrer">Ver portada actual</a>
              <?php endif; ?>
              <?php if (!empty($apiBlogCurrent['attachment_path_url'])): ?>
                <a class="im-chip" href="<?= $h($apiBlogCurrent['attachment_path_url']) ?>" target="_blank" rel="noreferrer">Ver adjunto actual</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <div class="im-formulario__acciones">
            <?php if ($apiBlogCurrent): ?>
              <a class="im-boton im-boton--texto" href="<?= $h($apiBlogBaseQuery) ?>">Cancelar</a>
              <button class="im-boton" type="submit" name="api_blog_submit" value="delete" formnovalidate>Desactivar</button>
            <?php else: ?>
              <button class="im-boton im-boton--texto" type="button" data-blog-dialog-close>Cancelar</button>
            <?php endif; ?>
            <button class="im-boton im-boton--principal" type="submit" name="api_blog_submit" value="save">Guardar publicacion</button>
          </div>
        </form>
      </div>
    </div>
  </dialog>

  <script>
    (() => {
      const dialog = document.querySelector('[data-blog-dialog]');
      if (!dialog) return;

      const openButtons = document.querySelectorAll('[data-blog-dialog-open]');
      const closeButtons = dialog.querySelectorAll('[data-blog-dialog-close]');

      const openDialog = () => {
        if (typeof dialog.showModal === 'function' && !dialog.open) {
          dialog.showModal();
        }
      };

      const closeDialog = () => {
        if (dialog.open) {
          dialog.close();
        }
      };

      openButtons.forEach((button) => {
        button.addEventListener('click', openDialog);
      });

      closeButtons.forEach((button) => {
        button.addEventListener('click', closeDialog);
      });

      dialog.addEventListener('click', (event) => {
        const bounds = dialog.getBoundingClientRect();
        const insideDialog =
          event.clientX >= bounds.left &&
          event.clientX <= bounds.right &&
          event.clientY >= bounds.top &&
          event.clientY <= bounds.bottom;

        if (!insideDialog) {
          closeDialog();
        }
      });

      if (dialog.dataset.autoOpen === 'true') {
        openDialog();
      }
    })();

  </script>
</section>
