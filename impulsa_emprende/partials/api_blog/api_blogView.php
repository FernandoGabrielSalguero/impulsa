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
?>
<section class="im-seccion-documento activa" id="api-blog-builder" data-panel="api-blog-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea"><?= $h($apiBlogRoleLabel) ?></p>
      <h2><?= $h($apiBlogPageTitle) ?></h2>
      <?php if ($apiBlogPageDescription !== ''): ?>
        <p><?= $h($apiBlogPageDescription) ?></p>
      <?php endif; ?>
    </div>
    <a class="im-boton im-boton--texto" href="<?= $h($apiBlogBackHref) ?>"><?= $h($apiBlogBackLabel) ?></a>
  </div>

  <?php if (is_array($apiBlogFlash) && trim((string) ($apiBlogFlash['mensaje'] ?? '')) !== ''): ?>
    <div class="im-alerta <?= ($apiBlogFlash['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
      <?= $h($apiBlogFlash['mensaje'] ?? '') ?>
    </div>
  <?php endif; ?>

  <div class="im-grilla im-grilla--dashboard">
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Listado de publicaciones</h3>
          <p>Selecciona una integracion para ver y administrar sus posteos.</p>
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
        <div class="im-tabla-contenedor">
          <table class="im-tabla">
            <thead><tr><th>Titulo</th><th>Slug</th><th>Estado</th><th>Publicacion</th><th>Acciones</th></tr></thead>
            <tbody>
              <?php foreach ($apiBlogItems as $item): ?>
                <tr>
                  <td><?= $h($item['title'] ?? '') ?></td>
                  <td><code><?= $h($item['slug'] ?? '') ?></code></td>
                  <td><span class="im-chip <?= ($item['status'] ?? 'draft') === 'active' ? 'im-chip--completado' : (($item['status'] ?? 'draft') === 'draft' ? 'im-chip--pendiente' : 'im-chip--alerta') ?>"><?= $h($item['status'] ?? 'draft') ?></span></td>
                  <td><?= $h($item['publication_date'] ?? '-') ?></td>
                  <td>
                    <div class="im-chip-lista">
                      <a class="im-boton im-boton--texto" href="?integration_id=<?= (int) ($apiBlogSelectedIntegration['id'] ?? 0) ?>&edit_id=<?= (int) ($item['id'] ?? 0) ?>">Editar</a>
                      <form method="post">
                        <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
                        <input type="hidden" name="item_id" value="<?= (int) ($item['id'] ?? 0) ?>">
                        <input type="hidden" name="target_status" value="<?= ($item['status'] ?? 'draft') === 'active' ? 'inactive' : 'active' ?>">
                        <button class="im-boton im-boton--texto" type="submit" name="api_blog_submit" value="toggle_status"><?= ($item['status'] ?? 'draft') === 'active' ? 'Desactivar' : 'Activar' ?></button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </article>

    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3><?= $apiBlogCurrent ? 'Editar publicacion' : 'Nueva publicacion' ?></h3>
          <p>Configura contenido, estado, portada y adjunto opcional.</p>
        </div>
      </div>

      <form method="post" enctype="multipart/form-data" class="im-formulario" data-quill-form>
        <input type="hidden" name="api_blog_action_scope" value="<?= $h($apiBlogPostAction) ?>">
        <input type="hidden" name="item_id" value="<?= (int) ($apiBlogCurrent['id'] ?? 0) ?>">
        <input type="hidden" name="description_html" value="<?= $h($apiBlogDescriptionValue) ?>" data-quill-hidden>

        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Integracion asociada</span>
          <select name="api_integration_id" required>
            <?php foreach ($apiBlogIntegraciones as $integration): ?>
              <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($apiBlogSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
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
          <span>Slug</span>
          <input type="text" name="slug" maxlength="220" value="<?= $h($apiBlogCurrent['slug'] ?? '') ?>" placeholder="se-autogenera-si-lo-dejas-vacio">
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
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Extracto</span>
          <textarea name="excerpt" rows="3" maxlength="300"><?= $h($apiBlogCurrent['excerpt'] ?? '') ?></textarea>
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Bibliografia</span>
          <textarea name="bibliography" rows="3"><?= $h($apiBlogCurrent['bibliography'] ?? '') ?></textarea>
        </label>
        <label class="im-campo im-campo-material">
          <span>Orden</span>
          <input type="number" name="sort_order" min="1" value="<?= (int) ($apiBlogCurrent['sort_order'] ?? 1) ?>">
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Metadata JSON</span>
          <textarea name="metadata_json" rows="3"><?= $h($apiBlogCurrent['metadata_json'] ?? '') ?></textarea>
        </label>

        <div class="im-campo im-campo--ancho">
          <span>Descripcion enriquecida</span>
          <div class="im-muestra" data-quill-editor style="min-height:220px;"><?= $apiBlogDescriptionValue ?></div>
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
            <a class="im-boton im-boton--texto" href="?integration_id=<?= (int) ($apiBlogSelectedIntegration['id'] ?? 0) ?>">Nueva publicacion</a>
            <button class="im-boton" type="submit" name="api_blog_submit" value="delete" formnovalidate>Desactivar</button>
          <?php endif; ?>
          <button class="im-boton im-boton--principal" type="submit" name="api_blog_submit" value="save">Guardar publicacion</button>
        </div>
      </form>
    </article>
  </div>
</section>
