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
$apiBlogResolverImagen = static function (?string $path, ?string $resolvedUrl = null): array {
    $candidatos = [];
    $agregar = static function (?string $valor) use (&$candidatos): void {
        $valor = trim((string) $valor);
        if ($valor !== '' && !in_array($valor, $candidatos, true)) {
            $candidatos[] = $valor;
        }
    };

    $agregar($resolvedUrl);
    $path = trim((string) $path);
    if ($path === '') {
        return $candidatos;
    }

    if (preg_match('#^https?://#i', $path)) {
        $agregar($path);
        return $candidatos;
    }

    $normalizado = '/' . ltrim(str_replace('\\', '/', $path), '/');
    $agregar($normalizado);

    if (str_starts_with($normalizado, '/impulsa_emprende/')) {
        $agregar('/' . ltrim(substr($normalizado, strlen('/impulsa_emprende/')), '/'));
    } else {
        $agregar('/impulsa_emprende' . $normalizado);
    }

    return $candidatos;
};
$apiBlogEmptyState = [
    'item_id' => 0,
    'api_integration_id' => (int) ($apiBlogSelectedIntegration['id'] ?? 0),
    'slug' => '',
    'description_html' => '<p></p>',
    'excerpt' => '',
    'title' => '',
    'subtitle' => '',
    'author' => '',
    'category' => '',
    'subcategory' => '',
    'status' => 'draft',
    'publication_date' => '',
    'sort_order' => 1,
    'bibliography' => '',
];
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
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1rem;
      align-items: start;
    }

    .im-blog-card {
      border: 1px solid rgba(15, 23, 42, .08);
      border-radius: 24px;
      background: linear-gradient(180deg, rgba(255, 255, 255, .98) 0%, rgba(248, 250, 252, .94) 100%);
      overflow: hidden;
      box-shadow: 0 18px 40px rgba(15, 23, 42, .08);
    }

    .im-blog-card__media {
      aspect-ratio: 16 / 9;
      background:
        linear-gradient(135deg, rgba(59, 130, 246, .18) 0%, rgba(16, 185, 129, .18) 100%),
        linear-gradient(180deg, rgba(241, 245, 249, 1) 0%, rgba(226, 232, 240, 1) 100%);
      overflow: hidden;
    }

    .im-blog-card__media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .im-blog-card__body,
    .im-blog-card__head,
    .im-blog-card__meta,
    .im-blog-card__actions {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
    }

    .im-blog-card__body {
      flex-direction: column;
      padding: 1.25rem;
      min-height: 100%;
    }

    .im-blog-card__head,
    .im-blog-card__meta,
    .im-blog-card__actions {
      align-items: center;
      justify-content: space-between;
      margin-top: auto;
    }

    .im-blog-card__head h4,
    .im-blog-card__head p {
      margin-top: 0;
    }

    .im-blog-card__head h4 {
      margin-bottom: .35rem;
      font-size: 1rem;
      line-height: 1.35;
    }

    .im-blog-card__head p {
      margin-bottom: 0;
      font-size: .92rem;
      line-height: 1.45;
    }

    .im-blog-card__head p,
    .im-blog-card__empty {
      color: rgba(15, 23, 42, .78);
    }

    .im-blog-card__meta {
      justify-content: flex-start;
      margin: 0;
    }

    .im-blog-card__excerpt {
      margin: 0;
      color: rgba(15, 23, 42, .74);
      font-size: .94rem;
      line-height: 1.5;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
      overflow: hidden;
    }

    .im-blog-card__empty {
      margin: 0;
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
      cursor: text;
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
      cursor: text;
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

    .im-blog-view-content {
      display: grid;
      gap: 1rem;
      line-height: 1.65;
      color: rgba(15, 23, 42, .88);
    }

    .im-blog-view-cover {
      width: 100%;
      max-height: 320px;
      object-fit: cover;
      border-radius: 18px;
      display: block;
    }

    .im-blog-view-richtext {
      border-top: 1px solid rgba(15, 23, 42, .08);
      padding-top: 1rem;
      overflow-wrap: anywhere;
    }

    .im-blog-view-richtext img {
      max-width: 100%;
      height: auto;
      border-radius: 16px;
    }

    .im-blog-current-cover {
      width: 100%;
      max-width: 280px;
      aspect-ratio: 16 / 9;
      object-fit: cover;
      border-radius: 16px;
      border: 1px solid rgba(15, 23, 42, .08);
      display: block;
    }

    @media (max-width: 720px) {
      .im-blog-grid {
        grid-template-columns: 1fr;
      }

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

    @media (min-width: 721px) and (max-width: 1100px) {
      .im-blog-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (min-width: 1101px) and (max-width: 1399px) {
      .im-blog-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
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

      <br>

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
            $itemExcerpt = trim((string) ($item['excerpt'] ?? ''));
            $itemCoverCandidates = $apiBlogResolverImagen($item['cover_image_path'] ?? null, $item['cover_image_path_url'] ?? null);
            ?>
            <article class="im-blog-card">
              <div class="im-blog-card__media">
                <?php if ($itemCoverCandidates !== []): ?>
                  <img
                    src="<?= $h($itemCoverCandidates[0] ?? '') ?>"
                    alt="<?= $h($item['title'] ?? 'Portada del blog') ?>"
                    loading="lazy"
                    data-image-fallbacks='<?= $h(json_encode($itemCoverCandidates, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]') ?>'
                  >
                <?php endif; ?>
              </div>

              <div class="im-blog-card__body">
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
                  <?php if (trim((string) ($item['author'] ?? '')) !== ''): ?>
                    <span class="im-chip">Autor: <?= $h($item['author'] ?? '') ?></span>
                  <?php endif; ?>
                  <span class="im-chip">Publicacion: <?= $h($item['publication_date'] ?? '-') ?></span>
                </div>

                <?php if ($itemExcerpt !== ''): ?>
                  <p class="im-blog-card__excerpt"><?= $h($itemExcerpt) ?></p>
                <?php else: ?>
                  <p class="im-blog-card__empty">Esta publicacion todavia no tiene extracto.</p>
                <?php endif; ?>

                <div class="im-blog-card__actions">
                  <div class="im-chip-lista">
                    <button
                      class="im-boton im-boton--texto"
                      type="button"
                      data-blog-view-open
                      data-blog-view='<?= $h(json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}') ?>'
                      data-blog-cover-fallbacks='<?= $h(json_encode($itemCoverCandidates, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]') ?>'
                    >
                      Ver
                    </button>
                    <a class="im-boton im-boton--texto" href="<?= $h($apiBlogBaseQuery . '&edit_id=' . (int) ($item['id'] ?? 0)) ?>">Editar</a>
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
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>
  </div>

  <dialog class="im-blog-dialog" data-blog-view-dialog>
    <div class="im-blog-dialog__shell">
      <div class="im-blog-dialog__header">
        <div>
          <h3 data-blog-view-title>Publicacion</h3>
          <p data-blog-view-subtitle>Detalle completo del blog seleccionado.</p>
        </div>
        <button class="im-boton im-boton--texto" type="button" data-blog-view-close>Cerrar</button>
      </div>

      <div class="im-blog-dialog__body">
        <div class="im-blog-view-content">
          <img class="im-blog-view-cover" data-blog-view-cover alt="" hidden>
          <div class="im-chip-lista">
            <span class="im-chip" data-blog-view-status></span>
            <span class="im-chip" data-blog-view-category hidden></span>
            <span class="im-chip" data-blog-view-author hidden></span>
            <span class="im-chip" data-blog-view-date></span>
          </div>
          <p data-blog-view-excerpt></p>
          <div class="im-blog-view-richtext" data-blog-view-content></div>
          <div class="im-chip-lista">
            <a class="im-chip" href="#" data-blog-view-attachment target="_blank" rel="noreferrer" hidden>Ver adjunto</a>
          </div>
        </div>
      </div>
    </div>
  </dialog>

  <dialog
    class="im-blog-dialog"
    data-blog-dialog
    <?= $apiBlogShouldOpenDialog ? 'data-auto-open="true"' : '' ?>
  >
    <div class="im-blog-dialog__shell">
      <div class="im-blog-dialog__header">
        <div>
          <h3 data-blog-form-title><?= $apiBlogCurrent ? 'Editar publicacion' : 'Nueva publicacion' ?></h3>
          <p data-blog-form-subtitle>Completa los campos del blog y guarda la publicacion cuando el contenido este listo.</p>
        </div>
        <button class="im-boton im-boton--texto" type="button" data-blog-dialog-close>Cerrar</button>
      </div>

      <div class="im-blog-dialog__body">
        <form method="post" enctype="multipart/form-data" class="im-formulario im-blog-form-layout" data-quill-form>
          <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
          <input type="hidden" name="item_id" value="<?= (int) ($apiBlogCurrent['id'] ?? 0) ?>" data-blog-item-id>
          <input type="hidden" name="slug" value="<?= $h($apiBlogCurrent['slug'] ?? '') ?>" data-blog-slug>
          <input type="hidden" name="description_html" value="<?= $h($apiBlogDescriptionValue) ?>" data-quill-hidden>
          <input type="hidden" name="excerpt" value="<?= $h($apiBlogCurrent['excerpt'] ?? '') ?>" data-blog-excerpt-hidden>
          <input type="hidden" data-blog-empty-state value="<?= $h(json_encode($apiBlogEmptyState, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}') ?>">

          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Integracion asociada</span>
            <select name="api_integration_id" required data-blog-field="api_integration_id">
              <?php foreach ($apiBlogIntegraciones as $integration): ?>
                <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) (($apiBlogCurrent['api_integration_id'] ?? $apiBlogSelectedIntegration['id'] ?? 0)) ? 'selected' : '' ?>>
                  <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="im-campo im-campo-material">
            <span>Titulo</span>
            <input type="text" name="title" maxlength="180" value="<?= $h($apiBlogCurrent['title'] ?? '') ?>" required data-blog-field="title">
          </label>
          <label class="im-campo im-campo-material">
            <span>Subtitulo</span>
            <input type="text" name="subtitle" maxlength="255" value="<?= $h($apiBlogCurrent['subtitle'] ?? '') ?>" data-blog-field="subtitle">
          </label>
          <label class="im-campo im-campo-material">
            <span>Autor</span>
            <input type="text" name="author" maxlength="180" value="<?= $h($apiBlogCurrent['author'] ?? '') ?>" data-blog-field="author">
          </label>
          <label class="im-campo im-campo-material">
            <span>Categoria</span>
            <input type="text" name="category" maxlength="120" value="<?= $h($apiBlogCurrent['category'] ?? '') ?>" data-blog-field="category">
          </label>
          <label class="im-campo im-campo-material">
            <span>Subcategoria</span>
            <input type="text" name="subcategory" maxlength="120" value="<?= $h($apiBlogCurrent['subcategory'] ?? '') ?>" data-blog-field="subcategory">
          </label>
          <label class="im-campo im-campo-material">
            <span>Estado</span>
            <select name="status" data-blog-field="status">
              <option value="draft" <?= ($apiBlogCurrent['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option>
              <option value="active" <?= ($apiBlogCurrent['status'] ?? '') === 'active' ? 'selected' : '' ?>>Activo</option>
              <option value="inactive" <?= ($apiBlogCurrent['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
            </select>
          </label>
          <label class="im-campo im-campo-material">
            <span>Fecha de publicacion</span>
            <input type="datetime-local" name="publication_date" value="<?= isset($apiBlogCurrent['publication_date']) && $apiBlogCurrent['publication_date'] ? $h(date('Y-m-d\TH:i', strtotime((string) $apiBlogCurrent['publication_date']))) : '' ?>" data-blog-field="publication_date">
          </label>
          <label class="im-campo im-campo-material">
            <span>Orden</span>
            <input type="number" name="sort_order" min="1" value="<?= (int) ($apiBlogCurrent['sort_order'] ?? 1) ?>" data-blog-field="sort_order">
          </label>
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Bibliografia</span>
            <textarea name="bibliography" rows="3" data-blog-field="bibliography"><?= $h($apiBlogCurrent['bibliography'] ?? '') ?></textarea>
          </label>
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Extracto</span>
            <textarea
              name="excerpt"
              rows="4"
              maxlength="300"
              data-blog-excerpt-editor
              data-blog-field="excerpt"
            ><?= $h($apiBlogCurrent['excerpt'] ?? '') ?></textarea>
          </label>

          <div class="im-campo im-campo--ancho">
            <span>Contenido de la publicacion</span>
            <div class="im-blog-editor im-muestra" data-quill-editor data-placeholder="Escribe aqui el cuerpo completo de la publicacion..."><?= $apiBlogDescriptionValue ?></div>
            <p class="im-blog-form-help">Haz click en cualquier parte del editor para escribir. El extracto se edita manualmente en su propio campo.</p>
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
              <?php $currentCoverCandidates = $apiBlogResolverImagen($apiBlogCurrent['cover_image_path'] ?? null, $apiBlogCurrent['cover_image_path_url'] ?? null); ?>
              <?php if ($currentCoverCandidates !== []): ?>
                <img
                  class="im-blog-current-cover"
                  src="<?= $h($currentCoverCandidates[0] ?? '') ?>"
                  alt="Portada actual"
                  data-image-fallbacks='<?= $h(json_encode($currentCoverCandidates, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]') ?>'
                >
              <?php endif; ?>
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
      const bindFallbacks = (image, fallbackList) => {
        const fallbacks = Array.isArray(fallbackList)
          ? fallbackList
          : JSON.parse(image.getAttribute('data-image-fallbacks') || '[]');
        let index = 0;
        image.dataset.imageFallbacksBound = 'true';

        image.addEventListener('error', () => {
          index += 1;
          if (index < fallbacks.length) {
            image.src = fallbacks[index];
            return;
          }

          image.hidden = true;
        });

        if (fallbacks.length > 0) {
          image.src = fallbacks[0];
        }
      };

      document.querySelectorAll('[data-image-fallbacks]').forEach((image) => {
        if (image.dataset.imageFallbacksBound === 'true') return;
        bindFallbacks(image);
      });

      window.imBindImageFallbacks = bindFallbacks;
    })();

    (() => {
      const viewDialog = document.querySelector('[data-blog-view-dialog]');
      if (!viewDialog) return;

      const titleNode = viewDialog.querySelector('[data-blog-view-title]');
      const subtitleNode = viewDialog.querySelector('[data-blog-view-subtitle]');
      const statusNode = viewDialog.querySelector('[data-blog-view-status]');
      const categoryNode = viewDialog.querySelector('[data-blog-view-category]');
      const authorNode = viewDialog.querySelector('[data-blog-view-author]');
      const dateNode = viewDialog.querySelector('[data-blog-view-date]');
      const excerptNode = viewDialog.querySelector('[data-blog-view-excerpt]');
      const contentNode = viewDialog.querySelector('[data-blog-view-content]');
      const coverNode = viewDialog.querySelector('[data-blog-view-cover]');
      const attachmentNode = viewDialog.querySelector('[data-blog-view-attachment]');

      document.querySelectorAll('[data-blog-view-open]').forEach((button) => {
        button.addEventListener('click', () => {
          const data = JSON.parse(button.dataset.blogView || '{}');

          titleNode.textContent = data.title || 'Publicacion';
          subtitleNode.textContent = data.subtitle || 'Detalle completo del blog seleccionado.';
          statusNode.textContent = data.status || 'draft';
          dateNode.textContent = 'Publicacion: ' + (data.publication_date || '-');
          excerptNode.textContent = data.excerpt || '';
          contentNode.innerHTML = data.description_html || '<p>Sin contenido.</p>';

          categoryNode.textContent = data.category || '';
          categoryNode.hidden = !data.category;
          authorNode.textContent = data.author ? 'Autor: ' + data.author : '';
          authorNode.hidden = !data.author;

          const candidates = JSON.parse(button.dataset.blogCoverFallbacks || '[]');
          if (candidates.length || data.cover_image_path_url || data.cover_image_path) {
            coverNode.alt = data.title || 'Portada de la publicacion';
            coverNode.hidden = false;
            coverNode.dataset.imageFallbacksBound = 'false';
            if (typeof window.imBindImageFallbacks === 'function') {
              window.imBindImageFallbacks(coverNode, candidates.length ? candidates : [data.cover_image_path_url]);
            } else {
              coverNode.src = candidates[0] || data.cover_image_path_url;
            }
          } else {
            coverNode.hidden = true;
            coverNode.removeAttribute('src');
          }

          if (data.attachment_path_url) {
            attachmentNode.href = data.attachment_path_url;
            attachmentNode.hidden = false;
          } else {
            attachmentNode.hidden = true;
            attachmentNode.removeAttribute('href');
          }

          if (!viewDialog.open) {
            viewDialog.showModal();
          }
        });
      });

      viewDialog.querySelectorAll('[data-blog-view-close]').forEach((button) => {
        button.addEventListener('click', () => viewDialog.close());
      });
    })();

    (() => {
      const dialog = document.querySelector('[data-blog-dialog]');
      if (!dialog) return;

      const form = dialog.querySelector('[data-quill-form]');
      const formTitle = dialog.querySelector('[data-blog-form-title]');
      const formSubtitle = dialog.querySelector('[data-blog-form-subtitle]');
      const openButtons = document.querySelectorAll('[data-blog-dialog-open]');
      const closeButtons = dialog.querySelectorAll('[data-blog-dialog-close]');
      const emptyStateNode = form.querySelector('[data-blog-empty-state]');
      const emptyState = JSON.parse(emptyStateNode?.value || '{}');
      const hiddenHtml = form.querySelector('[data-quill-hidden]');
      const hiddenExcerpt = form.querySelector('[data-blog-excerpt-hidden]');
      const excerptEditor = form.querySelector('[data-blog-excerpt-editor]');

      const resetFormState = () => {
        form.reset();

        form.querySelectorAll('[data-blog-field]').forEach((field) => {
          const key = field.dataset.blogField;
          const value = Object.prototype.hasOwnProperty.call(emptyState, key) ? emptyState[key] : '';
          field.value = value ?? '';
        });

        const itemIdField = form.querySelector('[data-blog-item-id]');
        const slugField = form.querySelector('[data-blog-slug]');
        if (itemIdField) itemIdField.value = String(emptyState.item_id ?? 0);
        if (slugField) slugField.value = emptyState.slug ?? '';
        if (hiddenHtml) hiddenHtml.value = emptyState.description_html ?? '<p></p>';
        if (hiddenExcerpt) hiddenExcerpt.value = emptyState.excerpt ?? '';
        if (excerptEditor) excerptEditor.value = emptyState.excerpt ?? '';
        if (formTitle) formTitle.textContent = 'Nueva publicacion';
        if (formSubtitle) formSubtitle.textContent = 'Completa los campos del blog y guarda la publicacion cuando el contenido este listo.';

        if (form._quillInstance) {
          form._quillInstance.setContents([]);
          form._quillInstance.root.innerHTML = hiddenHtml?.value || '<p></p>';
          form._quillInstance.focus();
        }
      };

      const openDialog = (mode = 'edit') => {
        if (mode === 'new') {
          resetFormState();
        } else {
          if (formTitle) formTitle.textContent = 'Editar publicacion';
          if (formSubtitle) formSubtitle.textContent = 'Actualiza los datos del blog y guarda los cambios.';
        }

        if (typeof dialog.showModal === 'function' && !dialog.open) {
          dialog.showModal();
        }

        if (mode === 'new' && form._quillInstance) {
          setTimeout(() => form._quillInstance.focus(), 50);
        }
      };

      const closeDialog = () => {
        if (dialog.open) {
          dialog.close();
        }
      };

      openButtons.forEach((button) => {
        button.addEventListener('click', () => openDialog('new'));
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
        openDialog('edit');
      }
    })();

    (() => {
      document.querySelectorAll('[data-quill-form]').forEach((form) => {
        const editorNode = form.querySelector('[data-quill-editor]');
        const htmlHidden = form.querySelector('[data-quill-hidden]');
        const excerptHidden = form.querySelector('[data-blog-excerpt-hidden]');
        const excerptEditor = form.querySelector('[data-blog-excerpt-editor]');

        if (!editorNode || !htmlHidden || typeof Quill === 'undefined' || form.dataset.quillInitialized === 'true') return;

        const quill = new Quill(editorNode, {
          theme: 'snow',
          placeholder: editorNode.dataset.placeholder || '',
          modules: {
            toolbar: [
              [{ header: [1, 2, 3, false] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ color: [] }, { background: [] }],
              [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
              [{ align: [] }],
              ['blockquote', 'code-block'],
              ['link', 'image', 'video'],
              ['clean']
            ]
          }
        });

        quill.root.innerHTML = htmlHidden.value || '<p></p>';
        form._quillInstance = quill;

        editorNode.addEventListener('click', () => quill.focus());

        const syncFields = () => {
          htmlHidden.value = quill.root.innerHTML;
        };

        if (excerptEditor && excerptHidden) {
          const syncExcerpt = () => {
            excerptHidden.value = excerptEditor.value.slice(0, 300);
          };

          excerptEditor.addEventListener('input', syncExcerpt);
          syncExcerpt();
        }

        quill.on('text-change', syncFields);
        form.addEventListener('submit', syncFields);
        form.dataset.quillInitialized = 'true';
        syncFields();
      });
    })();
  </script>
</section>
