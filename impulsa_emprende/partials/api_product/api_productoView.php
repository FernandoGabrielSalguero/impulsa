<?php

declare(strict_types=1);

$h = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$apiProductoFlash = $apiProductoFlash ?? null;
$apiProductoIntegraciones = $apiProductoIntegraciones ?? [];
$apiProductoItems = $apiProductoItems ?? [];
$apiProductoEditingItem = $apiProductoEditingItem ?? null;
$apiProductoSelectedIntegration = $apiProductoSelectedIntegration ?? null;
$apiProductoCurrent = is_array($apiProductoEditingItem) ? $apiProductoEditingItem : [];
$apiProductoDescriptionValue = (string) ($apiProductoCurrent['description_html'] ?? '<p></p>');
?>
<section class="im-seccion-documento activa" id="api-product-builder" data-panel="api-product-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea"><?= $h($apiProductoRoleLabel) ?></p>
      <h2><?= $h($apiProductoPageTitle) ?></h2>
      <?php if ($apiProductoPageDescription !== ''): ?>
        <p><?= $h($apiProductoPageDescription) ?></p>
      <?php endif; ?>
    </div>
    <a class="im-boton im-boton--texto" href="<?= $h($apiProductoBackHref) ?>"><?= $h($apiProductoBackLabel) ?></a>
  </div>

  <?php if (is_array($apiProductoFlash) && trim((string) ($apiProductoFlash['mensaje'] ?? '')) !== ''): ?>
    <div class="im-alerta <?= ($apiProductoFlash['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
      <?= $h($apiProductoFlash['mensaje'] ?? '') ?>
    </div>
  <?php endif; ?>

  <div class="im-grilla im-grilla--dashboard">
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Listado de productos</h3>
          <p>Selecciona una integracion para ver y administrar su catalogo.</p>
        </div>
      </div>

      <form method="get" class="im-formulario">
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Integracion asociada</span>
          <select name="integration_id" onchange="this.form.submit()">
            <?php foreach ($apiProductoIntegraciones as $integration): ?>
              <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($apiProductoSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
      </form>

      <?php if (!$apiProductoIntegraciones): ?>
        <div class="im-alerta im-alerta--info">Todavia no hay integraciones accesibles para tu cuenta.</div>
      <?php elseif (!$apiProductoItems): ?>
        <div class="im-alerta im-alerta--info">Aun no hay productos para esta integracion.</div>
      <?php else: ?>
        <div class="im-tabla-contenedor">
          <table class="im-tabla">
            <thead><tr><th>Titulo</th><th>SKU</th><th>Estado</th><th>Precio</th><th>Acciones</th></tr></thead>
            <tbody>
              <?php foreach ($apiProductoItems as $item): ?>
                <tr>
                  <td><?= $h($item['title'] ?? '') ?></td>
                  <td><code><?= $h($item['sku'] ?? '-') ?></code></td>
                  <td><span class="im-chip <?= ($item['status'] ?? 'draft') === 'active' ? 'im-chip--completado' : (($item['status'] ?? 'draft') === 'draft' ? 'im-chip--pendiente' : 'im-chip--alerta') ?>"><?= $h($item['status'] ?? 'draft') ?></span></td>
                  <td><?= $h($item['price'] !== null && $item['price'] !== '' ? ((string) $item['currency']) . ' ' . (string) $item['price'] : '-') ?></td>
                  <td>
                    <div class="im-chip-lista">
                      <a class="im-boton im-boton--texto" href="?integration_id=<?= (int) ($apiProductoSelectedIntegration['id'] ?? 0) ?>&edit_id=<?= (int) ($item['id'] ?? 0) ?>">Editar</a>
                      <form method="post">
                        <input type="hidden" name="api_producto_action_scope" value="<?= $h($apiProductoPostAction) ?>">
                        <input type="hidden" name="item_id" value="<?= (int) ($item['id'] ?? 0) ?>">
                        <input type="hidden" name="target_status" value="<?= ($item['status'] ?? 'draft') === 'active' ? 'inactive' : 'active' ?>">
                        <button class="im-boton im-boton--texto" type="submit" name="api_producto_submit" value="toggle_status"><?= ($item['status'] ?? 'draft') === 'active' ? 'Desactivar' : 'Activar' ?></button>
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
          <h3><?= $apiProductoCurrent ? 'Editar producto' : 'Nuevo producto' ?></h3>
          <p>Configura contenido, stock, precios, imagenes y adjunto opcional.</p>
        </div>
      </div>

      <form method="post" enctype="multipart/form-data" class="im-formulario" data-quill-form>
        <input type="hidden" name="api_producto_action_scope" value="<?= $h($apiProductoPostAction) ?>">
        <input type="hidden" name="item_id" value="<?= (int) ($apiProductoCurrent['id'] ?? 0) ?>">
        <input type="hidden" name="description_html" value="<?= $h($apiProductoDescriptionValue) ?>" data-quill-hidden>
        <input type="hidden" name="slug" value="<?= $h($apiProductoCurrent['slug'] ?? '') ?>">

        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Integracion asociada</span>
          <select name="api_integration_id" required>
            <?php foreach ($apiProductoIntegraciones as $integration): ?>
              <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($apiProductoSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Titulo</span>
          <input type="text" name="title" maxlength="180" value="<?= $h($apiProductoCurrent['title'] ?? '') ?>" required>
        </label>
        <label class="im-campo im-campo-material">
          <span>SKU</span>
          <input type="text" name="sku" maxlength="80" value="<?= $h($apiProductoCurrent['sku'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Estado</span>
          <select name="status">
            <option value="draft" <?= ($apiProductoCurrent['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option>
            <option value="active" <?= ($apiProductoCurrent['status'] ?? '') === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= ($apiProductoCurrent['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Categoria</span>
          <input type="text" name="category" maxlength="120" value="<?= $h($apiProductoCurrent['category'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Subcategoria</span>
          <input type="text" name="subcategory" maxlength="120" value="<?= $h($apiProductoCurrent['subcategory'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Precio</span>
          <input type="number" step="0.01" name="price" value="<?= $h($apiProductoCurrent['price'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Moneda</span>
          <input type="text" name="currency" maxlength="8" value="<?= $h($apiProductoCurrent['currency'] ?? 'ARS') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Stock</span>
          <input type="number" name="stock_quantity" min="0" value="<?= $h($apiProductoCurrent['stock_quantity'] ?? '') ?>">
        </label>
        <label class="im-campo im-campo-material">
          <span>Disponibilidad</span>
          <select name="availability">
            <option value="in_stock" <?= ($apiProductoCurrent['availability'] ?? 'on_request') === 'in_stock' ? 'selected' : '' ?>>En stock</option>
            <option value="out_of_stock" <?= ($apiProductoCurrent['availability'] ?? 'on_request') === 'out_of_stock' ? 'selected' : '' ?>>Sin stock</option>
            <option value="preorder" <?= ($apiProductoCurrent['availability'] ?? 'on_request') === 'preorder' ? 'selected' : '' ?>>Preventa</option>
            <option value="on_request" <?= ($apiProductoCurrent['availability'] ?? 'on_request') === 'on_request' ? 'selected' : '' ?>>A pedido</option>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Orden</span>
          <input type="number" name="sort_order" min="1" value="<?= (int) ($apiProductoCurrent['sort_order'] ?? 1) ?>">
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Descripcion corta</span>
          <textarea name="short_description" rows="3" maxlength="300"><?= $h($apiProductoCurrent['short_description'] ?? '') ?></textarea>
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span><input type="checkbox" name="featured" value="1" <?= !empty($apiProductoCurrent['featured']) ? 'checked' : '' ?>> Destacar producto</span>
        </label>

        <div class="im-campo im-campo--ancho">
          <span>Descripcion enriquecida</span>
          <div class="im-muestra" data-quill-editor style="min-height:220px;"><?= $apiProductoDescriptionValue ?></div>
        </div>

        <label class="im-campo im-campo-material">
          <span>Imagen principal</span>
          <input type="file" name="main_image_file" accept=".jpg,.jpeg,.png,.webp">
        </label>
        <label class="im-campo im-campo-material">
          <span>Miniatura</span>
          <input type="file" name="thumbnail_file" accept=".jpg,.jpeg,.png,.webp">
        </label>
        <label class="im-campo im-campo-material">
          <span>Adjunto</span>
          <input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.txt">
        </label>

        <?php if (!empty($apiProductoCurrent['main_image_path_url']) || !empty($apiProductoCurrent['thumbnail_path_url']) || !empty($apiProductoCurrent['attachment_path_url'])): ?>
          <div class="im-chip-lista im-campo--ancho">
            <?php if (!empty($apiProductoCurrent['main_image_path_url'])): ?>
              <a class="im-chip" href="<?= $h($apiProductoCurrent['main_image_path_url']) ?>" target="_blank" rel="noreferrer">Ver imagen principal</a>
            <?php endif; ?>
            <?php if (!empty($apiProductoCurrent['thumbnail_path_url'])): ?>
              <a class="im-chip" href="<?= $h($apiProductoCurrent['thumbnail_path_url']) ?>" target="_blank" rel="noreferrer">Ver miniatura</a>
            <?php endif; ?>
            <?php if (!empty($apiProductoCurrent['attachment_path_url'])): ?>
              <a class="im-chip" href="<?= $h($apiProductoCurrent['attachment_path_url']) ?>" target="_blank" rel="noreferrer">Ver adjunto</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="im-formulario__acciones">
          <?php if ($apiProductoCurrent): ?>
            <a class="im-boton im-boton--texto" href="?integration_id=<?= (int) ($apiProductoSelectedIntegration['id'] ?? 0) ?>">Nuevo producto</a>
            <button class="im-boton" type="submit" name="api_producto_submit" value="delete" formnovalidate>Desactivar</button>
          <?php endif; ?>
          <button class="im-boton im-boton--principal" type="submit" name="api_producto_submit" value="save">Guardar producto</button>
        </div>
      </form>
    </article>
  </div>
</section>
