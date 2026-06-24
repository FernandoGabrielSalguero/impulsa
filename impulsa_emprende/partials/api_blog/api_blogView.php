<?php

declare(strict_types=1);

$h = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$apiBlogFlash = $apiBlogFlash ?? null;
$apiBlogIntegraciones = $apiBlogIntegraciones ?? [];
$apiBlogItems = $apiBlogItems ?? [];
$apiBlogEditingItem = $apiBlogEditingItem ?? null;
$apiBlogSelectedIntegration = $apiBlogSelectedIntegration ?? null;
$apiBlogCurrent = is_array($apiBlogEditingItem) ? $apiBlogEditingItem : [];

$apiBlogStatusLabels = [
  'draft' => ['Borrador', 'im-chip--pendiente'],
  'active' => ['Activo', 'im-chip--completado'],
  'inactive' => ['Inactivo', 'im-chip--alerta'],
];

$apiBlogInitialItem = $apiBlogCurrent !== [] ? [
  'id' => (int) ($apiBlogCurrent['id'] ?? 0),
  'api_integration_id' => (int) ($apiBlogCurrent['api_integration_id'] ?? 0),
  'title' => (string) ($apiBlogCurrent['title'] ?? ''),
  'subtitle' => (string) ($apiBlogCurrent['subtitle'] ?? ''),
  'author' => (string) ($apiBlogCurrent['author'] ?? ''),
  'status' => (string) ($apiBlogCurrent['status'] ?? 'draft'),
  'publication_date' => !empty($apiBlogCurrent['publication_date']) ? date('Y-m-d\TH:i', strtotime((string) $apiBlogCurrent['publication_date'])) : '',
  'category' => (string) ($apiBlogCurrent['category'] ?? ''),
  'subcategory' => (string) ($apiBlogCurrent['subcategory'] ?? ''),
  'sort_order' => (int) ($apiBlogCurrent['sort_order'] ?? 1),
  'excerpt' => (string) ($apiBlogCurrent['excerpt'] ?? ''),
  'description_html' => (string) ($apiBlogCurrent['description_html'] ?? ''),
  'bibliography' => (string) ($apiBlogCurrent['bibliography'] ?? ''),
  'metadata_json' => (string) ($apiBlogCurrent['metadata_json'] ?? ''),
  'cover_image_path_url' => (string) ($apiBlogCurrent['cover_image_path_url'] ?? ''),
  'attachment_path_url' => (string) ($apiBlogCurrent['attachment_path_url'] ?? ''),
  'attachment_name' => basename((string) ($apiBlogCurrent['attachment_path'] ?? '')),
  'cover_image_name' => basename((string) ($apiBlogCurrent['cover_image_path'] ?? '')),
] : [];
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

  .im-blog-modal {
    width: min(1040px, calc(100vw - 2rem));
    max-height: calc(100vh - 2rem);
  }

  .im-blog-modal .im-dialog__contenido {
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    display: grid;
    gap: 1rem;
    max-height: calc(100vh - 12rem);
    padding-right: .25rem;
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

  .im-blog-editor-wrap {
    display: grid;
    gap: .5rem;
  }

  .im-blog-editor-label {
    font-size: .9rem;
    color: var(--im-color-principal, #112c4e);
    font-weight: 600;
  }

  .im-blog-editor {
    background: var(--im-color-superficie, #fff);
    border: 1px solid var(--im-color-borde, #d0d7de);
    border-radius: 1rem;
    overflow: hidden;
  }

  .im-blog-editor .ql-toolbar.ql-snow {
    border: 0;
    border-bottom: 1px solid var(--im-color-borde, #d0d7de);
  }

  .im-blog-editor .ql-container.ql-snow {
    border: 0;
    min-height: 280px;
    font-family: inherit;
    font-size: 1rem;
  }

  .im-blog-preview-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }

  .im-blog-preview-card {
    border: 1px dashed var(--im-color-borde, #d0d7de);
    border-radius: 1rem;
    padding: 1rem;
    background: color-mix(in srgb, var(--im-color-superficie, #fff) 94%, var(--im-color-principal, #112c4e));
    display: grid;
    gap: .75rem;
    min-height: 180px;
  }

  .im-blog-preview-card h4 {
    margin: 0;
    font-size: 1rem;
  }

  .im-blog-preview-card p {
    margin: 0;
  }

  .im-blog-preview-image {
    width: 100%;
    min-height: 150px;
    max-height: 260px;
    object-fit: cover;
    border-radius: .75rem;
    background: #f3f4f6;
  }

  .im-blog-preview-empty {
    display: grid;
    place-items: center;
    border-radius: .75rem;
    min-height: 150px;
    background: #f7f7f8;
    color: #6b7280;
    text-align: center;
    padding: 1rem;
  }

  .im-blog-preview-file {
    display: grid;
    gap: .5rem;
    align-content: start;
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
        <p>Administra solo los blogs creados con tu usuario dentro de cada integracion.</p>
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
        <button class="im-boton im-boton--principal" type="button" data-blog-open-modal="create">
          Nueva publicacion
        </button>
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
              $editPayload = [
                'id' => (int) ($item['id'] ?? 0),
                'api_integration_id' => (int) ($item['api_integration_id'] ?? 0),
                'title' => (string) ($item['title'] ?? ''),
                'subtitle' => (string) ($item['subtitle'] ?? ''),
                'author' => (string) ($item['author'] ?? ''),
                'status' => (string) ($item['status'] ?? 'draft'),
                'publication_date' => !empty($item['publication_date']) ? date('Y-m-d\TH:i', strtotime((string) $item['publication_date'])) : '',
                'category' => (string) ($item['category'] ?? ''),
                'subcategory' => (string) ($item['subcategory'] ?? ''),
                'sort_order' => (int) ($item['sort_order'] ?? 1),
                'excerpt' => (string) ($item['excerpt'] ?? ''),
                'description_html' => (string) ($item['description_html'] ?? ''),
                'bibliography' => (string) ($item['bibliography'] ?? ''),
                'metadata_json' => (string) ($item['metadata_json'] ?? ''),
                'cover_image_path_url' => (string) ($item['cover_image_path_url'] ?? ''),
                'attachment_path_url' => (string) ($item['attachment_path_url'] ?? ''),
                'attachment_name' => basename((string) ($item['attachment_path'] ?? '')),
                'cover_image_name' => basename((string) ($item['cover_image_path'] ?? '')),
              ];
              ?>
              <tr>
                <td class="im-blog-tabla-nombre">
                  <strong><?= $h($item['title'] ?? '') ?></strong>
                  <?php if (!empty($item['excerpt'])): ?>
                    <small><?= $h($item['excerpt']) ?></small>
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
                    <button
                      class="im-boton-icono material-symbols-rounded im-blog-accion--editar"
                      type="button"
                      data-blog-open-modal="edit"
                      data-blog-edit='<?= $h(json_encode($editPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>'
                      aria-label="Editar publicacion">edit</button>
                    <button
                      class="im-boton-icono material-symbols-rounded im-blog-accion--eliminar"
                      type="button"
                      data-blog-delete-open
                      data-blog-delete-id="<?= (int) ($item['id'] ?? 0) ?>"
                      data-blog-delete-title="<?= $h($item['title'] ?? 'Publicacion seleccionada') ?>"
                      aria-label="Eliminar publicacion">delete</button>
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
      <h3 id="api-blog-modal-titulo">Nueva publicacion</h3>
    </div>
    <button class="im-boton-icono" type="button" data-blog-close-modal aria-label="Cerrar dialog"></button>
  </header>

  <form method="post" enctype="multipart/form-data" data-blog-form data-quill-form>
    <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
    <input type="hidden" name="item_id" value="" data-blog-field="item_id">
    <input type="hidden" name="description_html" value="" data-quill-hidden data-blog-field="description_html">

    <div class="im-dialog__contenido">
      <div class="im-formulario">
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Integracion asociada</span>
          <select name="api_integration_id" required data-blog-field="api_integration_id">
            <?php foreach ($apiBlogIntegraciones as $integration): ?>
              <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($apiBlogSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Titulo</span>
          <input type="text" name="title" maxlength="180" value="" required data-blog-field="title">
        </label>
        <label class="im-campo im-campo-material">
          <span>Subtitulo</span>
          <input type="text" name="subtitle" maxlength="255" value="" data-blog-field="subtitle">
        </label>
        <label class="im-campo im-campo-material">
          <span>Autor</span>
          <input type="text" name="author" maxlength="180" value="" data-blog-field="author">
        </label>
        <label class="im-campo im-campo-material">
          <span>Estado</span>
          <select name="status" data-blog-field="status">
            <option value="draft">Borrador</option>
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Fecha de publicacion</span>
          <input type="datetime-local" name="publication_date" value="" data-blog-field="publication_date">
        </label>
        <label class="im-campo im-campo-material">
          <span>Categoria</span>
          <input type="text" name="category" maxlength="120" value="" data-blog-field="category">
        </label>
        <label class="im-campo im-campo-material">
          <span>Subcategoria</span>
          <input type="text" name="subcategory" maxlength="120" value="" data-blog-field="subcategory">
        </label>
        <label class="im-campo im-campo-material">
          <span>Orden</span>
          <input type="number" name="sort_order" min="1" value="1" data-blog-field="sort_order">
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Resumen</span>
          <textarea name="excerpt" rows="3" maxlength="300" placeholder=" " data-im-placeholder data-blog-field="excerpt"></textarea>
        </label>
        <div class="im-campo im-campo--ancho im-blog-editor-wrap"> 
          <span class="im-blog-editor-label">Texto del blog</span>
          <div class="im-blog-editor" data-quill-editor data-placeholder="Escribe el contenido del blog..."></div>
        </div>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Bibliografia</span>
          <textarea name="bibliography" rows="4" placeholder=" " data-im-placeholder data-blog-field="bibliography"></textarea>
        </label>

        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Información técnica adicional</span>
          <textarea name="metadata_json" rows="3" placeholder=" " data-im-placeholder data-blog-field="metadata_json"></textarea>
        </label>

        <label class="im-campo im-campo-material">
          <span>Imagen de portada</span>
          <input type="file" name="cover_image_file" accept=".jpg,.jpeg,.png,.webp" data-blog-cover-input>
        </label>
        <label class="im-campo im-campo-material">
          <span>Adjunto opcional</span>
          <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.txt" data-blog-attachment-input>
        </label>

        <div class="im-blog-preview-grid im-campo--ancho">
          <article class="im-blog-preview-card">
            <h4>Previsualizacion de portada</h4>
            <img class="im-blog-preview-image" src="" alt="Previsualizacion de portada" hidden data-blog-cover-preview-image>
            <div class="im-blog-preview-empty" data-blog-cover-preview-empty>No hay imagen cargada.</div>
            <a class="im-chip" href="#" target="_blank" rel="noreferrer" hidden data-blog-cover-preview-link>Ver portada actual</a>
            <label class="im-chip" hidden data-blog-cover-remove-wrap>
              <input type="checkbox" name="remove_cover_image" value="1" data-blog-remove-cover> Quitar portada
            </label>
          </article>

          <article class="im-blog-preview-card">
            <h4>Previsualizacion de adjunto</h4>
            <div class="im-blog-preview-file">
              <div class="im-blog-preview-empty" data-blog-attachment-preview-empty>No hay adjunto cargado.</div>
              <p hidden data-blog-attachment-preview-name></p>
              <a class="im-chip" href="#" target="_blank" rel="noreferrer" hidden data-blog-attachment-preview-link>Ver adjunto actual</a>
              <label class="im-chip" hidden data-blog-attachment-remove-wrap>
                <input type="checkbox" name="remove_attachment" value="1" data-blog-remove-attachment> Quitar adjunto
              </label>
            </div>
          </article>
        </div>
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
    const form = document.querySelector('[data-blog-form]');
    if (!modal || !cortina || !form) {
      return;
    }

    const modalTitle = modal.querySelector('#api-blog-modal-titulo');
    const hiddenHtml = form.querySelector('[data-quill-hidden]');
    const editorNode = form.querySelector('[data-quill-editor]');
    const coverInput = form.querySelector('[data-blog-cover-input]');
    const coverPreviewImage = form.querySelector('[data-blog-cover-preview-image]');
    const coverPreviewEmpty = form.querySelector('[data-blog-cover-preview-empty]');
    const coverPreviewLink = form.querySelector('[data-blog-cover-preview-link]');
    const coverRemoveWrap = form.querySelector('[data-blog-cover-remove-wrap]');
    const removeCover = form.querySelector('[data-blog-remove-cover]');
    const attachmentInput = form.querySelector('[data-blog-attachment-input]');
    const attachmentPreviewEmpty = form.querySelector('[data-blog-attachment-preview-empty]');
    const attachmentPreviewName = form.querySelector('[data-blog-attachment-preview-name]');
    const attachmentPreviewLink = form.querySelector('[data-blog-attachment-preview-link]');
    const attachmentRemoveWrap = form.querySelector('[data-blog-attachment-remove-wrap]');
    const removeAttachment = form.querySelector('[data-blog-remove-attachment]');
    const defaultIntegrationId = <?= (int) ($apiBlogSelectedIntegration['id'] ?? 0) ?>;
    const initialItem = <?= json_encode($apiBlogInitialItem, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    const fields = {
      item_id: form.querySelector('[data-blog-field="item_id"]'),
      api_integration_id: form.querySelector('[data-blog-field="api_integration_id"]'),
      title: form.querySelector('[data-blog-field="title"]'),
      subtitle: form.querySelector('[data-blog-field="subtitle"]'),
      author: form.querySelector('[data-blog-field="author"]'),
      status: form.querySelector('[data-blog-field="status"]'),
      publication_date: form.querySelector('[data-blog-field="publication_date"]'),
      category: form.querySelector('[data-blog-field="category"]'),
      subcategory: form.querySelector('[data-blog-field="subcategory"]'),
      sort_order: form.querySelector('[data-blog-field="sort_order"]'),
      excerpt: form.querySelector('[data-blog-field="excerpt"]'),
      bibliography: form.querySelector('[data-blog-field="bibliography"]'),
      metadata_json: form.querySelector('[data-blog-field="metadata_json"]')
    };

    let quill = null;
    let objectUrlCover = null;

    const dispatchInputEvents = (element) => {
      if (!element) return;
      element.dispatchEvent(new Event('input', {
        bubbles: true
      }));
      element.dispatchEvent(new Event('change', {
        bubbles: true
      }));
    };

    const initQuill = () => {
      if (!editorNode || !hiddenHtml || typeof Quill === 'undefined' || quill) {
        return;
      }

      let hasTableSupport = false;
      try {
        hasTableSupport = Boolean(Quill.import('formats/table'));
      } catch (error) {
        hasTableSupport = false;
      }

      const toolbarOptions = [
        [{
          header: [1, 2, 3, false]
        }],
        ['bold', 'italic', 'underline', 'strike'],
        [{
          color: []
        }, {
          background: []
        }],
        [{
          list: 'ordered'
        }, {
          list: 'bullet'
        }, {
          indent: '-1'
        }, {
          indent: '+1'
        }],
        [{
          align: []
        }],
        ['blockquote', 'code-block'],
        ['link', 'image', 'video']
      ];

      if (hasTableSupport) {
        toolbarOptions.push(['table']);
      }

      toolbarOptions.push(['clean']);

      quill = new Quill(editorNode, {
        theme: 'snow',
        placeholder: editorNode.dataset.placeholder || '',
        modules: {
          toolbar: toolbarOptions,
          ...(hasTableSupport ? {
            table: true
          } : {})
        }
      });

      quill.on('text-change', () => {
        hiddenHtml.value = quill.root.innerHTML;
      });
    };

    const setEditorHtml = (html) => {
      initQuill();
      const safeHtml = html && html.trim() !== '' ? html : '<p></p>';
      if (quill) {
        quill.root.innerHTML = safeHtml;
      }
      hiddenHtml.value = safeHtml;
    };

    const clearCoverObjectUrl = () => {
      if (objectUrlCover) {
        URL.revokeObjectURL(objectUrlCover);
        objectUrlCover = null;
      }
    };

    const refreshCoverPreview = (item = null) => {
      clearCoverObjectUrl();
      const file = coverInput?.files && coverInput.files[0] ? coverInput.files[0] : null;
      const existingUrl = item?.cover_image_path_url || '';
      const existingName = item?.cover_image_name || '';
      const removing = Boolean(removeCover?.checked);

      if (coverPreviewImage) {
        coverPreviewImage.hidden = true;
        coverPreviewImage.removeAttribute('src');
      }

      if (file) {
        objectUrlCover = URL.createObjectURL(file);
        coverPreviewImage.src = objectUrlCover;
        coverPreviewImage.hidden = false;
        coverPreviewEmpty.hidden = true;
        coverPreviewLink.hidden = true;
      } else if (existingUrl && !removing) {
        coverPreviewImage.src = existingUrl;
        coverPreviewImage.hidden = false;
        coverPreviewEmpty.hidden = true;
        coverPreviewLink.href = existingUrl;
        coverPreviewLink.textContent = existingName ? `Ver portada actual: ${existingName}` : 'Ver portada actual';
        coverPreviewLink.hidden = false;
      } else {
        coverPreviewEmpty.hidden = false;
        coverPreviewLink.hidden = true;
      }

      coverRemoveWrap.hidden = !(existingUrl);
    };

    const refreshAttachmentPreview = (item = null) => {
      const file = attachmentInput?.files && attachmentInput.files[0] ? attachmentInput.files[0] : null;
      const existingUrl = item?.attachment_path_url || '';
      const existingName = item?.attachment_name || '';
      const removing = Boolean(removeAttachment?.checked);

      if (file) {
        attachmentPreviewEmpty.hidden = true;
        attachmentPreviewName.textContent = file.name;
        attachmentPreviewName.hidden = false;
        attachmentPreviewLink.hidden = true;
      } else if (existingUrl && !removing) {
        attachmentPreviewEmpty.hidden = true;
        attachmentPreviewName.textContent = existingName || 'Adjunto actual';
        attachmentPreviewName.hidden = false;
        attachmentPreviewLink.href = existingUrl;
        attachmentPreviewLink.textContent = existingName ? `Ver adjunto actual: ${existingName}` : 'Ver adjunto actual';
        attachmentPreviewLink.hidden = false;
      } else {
        attachmentPreviewEmpty.hidden = false;
        attachmentPreviewName.hidden = true;
        attachmentPreviewLink.hidden = true;
      }

      attachmentRemoveWrap.hidden = !(existingUrl);
    };

    const getCleanUrl = () => {
      const url = new URL(window.location.href);
      url.searchParams.delete('edit_id');
      url.searchParams.delete('integration_id');
      return url.pathname;
    };

    const syncUrl = () => {
      window.history.replaceState({}, '', getCleanUrl());
    };

    const alternarModal = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');

      if (!abrir) {
        syncUrl();
      }
    };

    const resetForm = () => {
      form.reset();
      fields.item_id.value = '';
      if (fields.api_integration_id && defaultIntegrationId > 0) {
        fields.api_integration_id.value = String(defaultIntegrationId);
      }
      if (fields.status) fields.status.value = 'draft';
      if (fields.sort_order) fields.sort_order.value = '1';
      if (removeCover) removeCover.checked = false;
      if (removeAttachment) removeAttachment.checked = false;
      setEditorHtml('<p></p>');
      Object.values(fields).forEach((field) => dispatchInputEvents(field));
      refreshCoverPreview(null);
      refreshAttachmentPreview(null);
      modalTitle.textContent = 'Nueva publicacion';
    };

    const fillForm = (item) => {
      fields.item_id.value = item?.id ? String(item.id) : '';
      fields.api_integration_id.value = item?.api_integration_id ? String(item.api_integration_id) : String(defaultIntegrationId || '');
      fields.title.value = item?.title || '';
      fields.subtitle.value = item?.subtitle || '';
      fields.author.value = item?.author || '';
      fields.status.value = item?.status || 'draft';
      fields.publication_date.value = item?.publication_date || '';
      fields.category.value = item?.category || '';
      fields.subcategory.value = item?.subcategory || '';
      fields.sort_order.value = item?.sort_order ? String(item.sort_order) : '1';
      fields.excerpt.value = item?.excerpt || '';
      fields.bibliography.value = item?.bibliography || '';
      fields.metadata_json.value = item?.metadata_json || '';
      if (removeCover) removeCover.checked = false;
      if (removeAttachment) removeAttachment.checked = false;
      setEditorHtml(item?.description_html || '<p></p>');
      Object.values(fields).forEach((field) => dispatchInputEvents(field));
      refreshCoverPreview(item);
      refreshAttachmentPreview(item);
      modalTitle.textContent = item?.id ? 'Editar publicacion' : 'Nueva publicacion';
    };

    if (coverInput) {
      coverInput.addEventListener('change', () => refreshCoverPreview(initialItem));
    }
    if (attachmentInput) {
      attachmentInput.addEventListener('change', () => refreshAttachmentPreview(initialItem));
    }
    if (removeCover) {
      removeCover.addEventListener('change', () => refreshCoverPreview(initialItem));
    }
    if (removeAttachment) {
      removeAttachment.addEventListener('change', () => refreshAttachmentPreview(initialItem));
    }

    document.addEventListener('click', (evento) => {
      const createTrigger = evento.target.closest('[data-blog-open-modal="create"]');
      if (createTrigger) {
        resetForm();
        alternarModal(true);
        return;
      }

      const editTrigger = evento.target.closest('[data-blog-open-modal="edit"]');
      if (editTrigger) {
        try {
          fillForm(JSON.parse(editTrigger.getAttribute('data-blog-edit') || '{}'));
          alternarModal(true);
        } catch (error) {
          resetForm();
        }
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

    form.addEventListener('submit', () => {
      if (quill) {
        hiddenHtml.value = quill.root.innerHTML;
      }
      syncUrl();
    });

    resetForm();

    if (initialItem && initialItem.id) {
      fillForm(initialItem);
      alternarModal(true);
    }
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