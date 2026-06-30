<?php

$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$integraciones = $integraciones ?? [];
$selectedIntegration = $selectedIntegration ?? null;
$productos = $productos ?? [];
$editingProduct = $editingProduct ?? null;
$productosResumen = $productosResumen ?? [];
$flashProductos = $flashProductos ?? null;
$adminActiveMenu = 'productos-admin';
$h = static fn (mixed $value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
$productCurrent = is_array($editingProduct) ? $editingProduct : [];
$descriptionValue = (string) ($productCurrent['description_html'] ?? '<p></p>');
$availabilityOptions = [
    'in_stock' => 'En stock',
    'out_of_stock' => 'Sin stock',
    'preorder' => 'Preventa',
    'on_request' => 'A pedido',
];
$statusOptions = [
    'draft' => 'Borrador',
    'active' => 'Activo',
    'inactive' => 'Inactivo',
];
$formatDate = static function (?string $value): string {
    if (!$value) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($value));
};
$formatMoney = static function (mixed $value, string $currency): string {
    if ($value === null || $value === '') {
        return '-';
    }

    return trim($currency) . ' ' . number_format((float) $value, 2, ',', '.');
};
$basename = static fn (?string $path): string => $path ? basename(str_replace('\\', '/', $path)) : '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestor de productos admin | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <?= renderImpulsaMaterialFonts() ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.bubble.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-accion-salir { color: #ba1a1a; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
    .im-nav-item__icono[data-icon]::before { content: attr(data-icon); }
    .im-admin-productos-grid { display: grid; gap: 1rem; }
    .im-admin-productos-layout { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(360px, .9fr); gap: 1rem; align-items: start; }
    .im-admin-productos-meta { display: grid; gap: .75rem; }
    .im-admin-productos-meta__item { padding: .9rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-admin-productos-meta__item span { display: block; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-admin-productos-meta__item strong,
    .im-admin-productos-meta__item code { display: block; margin-top: .2rem; word-break: break-word; }
    .im-admin-productos-archivos { display: grid; gap: .5rem; }
    .im-admin-productos-archivo { color: var(--im-color-texto-suave); font-size: .85rem; }
    .im-admin-productos-cabecera { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; justify-content: space-between; }
    .im-admin-productos-selector { display: grid; gap: 1rem; }
    .im-admin-productos-note { margin: 0; color: var(--im-color-texto-suave); font-size: .85rem; line-height: 1.45; }
    @media (max-width: 980px) {
      .im-admin-productos-layout { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <?php require __DIR__ . '/adminMenu.php'; ?>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa</p>
            <h1>Gestor de productos</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="admin-productos-manager" data-panel="admin-productos-manager">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Productos de prueba para usuarios con API</h2>
              <p>Desde aca puedes cargar catalogos de apoyo y dejarlos asignados al usuario propietario de cada integracion.</p>
            </div>
          </div>

          <?php if (is_array($flashProductos) && trim((string) ($flashProductos['mensaje'] ?? '')) !== ''): ?>
            <div class="im-alerta <?= ($flashProductos['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
              <?= $h($flashProductos['mensaje'] ?? '') ?>
            </div>
          <?php endif; ?>

          <div class="im-grilla im-grilla--metricas">
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Productos</span><strong><?= (int) ($productosResumen['total_productos'] ?? 0) ?></strong><small>En la integracion seleccionada</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Activos</span><strong><?= (int) ($productosResumen['total_activos'] ?? 0) ?></strong><small>Publicados</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Borrador</span><strong><?= (int) ($productosResumen['total_borrador'] ?? 0) ?></strong><small>En preparacion</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Destacados</span><strong><?= (int) ($productosResumen['total_destacados'] ?? 0) ?></strong><small>Marcados como destacados</small></article>
          </div>

          <div class="im-admin-productos-grid">
            <article class="im-tarjeta im-admin-productos-selector">
              <div class="im-admin-productos-cabecera">
                <div>
                  <h3>Integracion objetivo</h3>
                  <p>Selecciona la API del usuario al que quieres asignarle productos de apoyo.</p>
                </div>
                <span class="im-chip"><?= count($integraciones) ?> integraciones con usuario</span>
              </div>

              <form method="get" class="im-formulario">
                <label class="im-campo im-campo-material im-campo--ancho">
                  <span>Integracion API</span>
                  <select name="integration_id" onchange="this.form.submit()">
                    <?php foreach ($integraciones as $integration): ?>
                      <option value="<?= (int) ($integration['id'] ?? 0) ?>" <?= (int) ($integration['id'] ?? 0) === (int) ($selectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                        <?= $h(($integration['project_name'] ?? '') . ' - ' . ($integration['owner_name'] ?? '') . ' - ' . ($integration['allowed_domain'] ?? '')) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </label>
              </form>

              <?php if ($selectedIntegration !== null): ?>
                <div class="im-admin-productos-meta">
                  <div class="im-admin-productos-meta__item">
                    <span>Proyecto</span>
                    <strong><?= $h($selectedIntegration['project_name'] ?? '') ?></strong>
                  </div>
                  <div class="im-admin-productos-meta__item">
                    <span>Usuario asignado</span>
                    <strong><?= $h($selectedIntegration['owner_name'] ?? '') ?></strong>
                    <span><?= $h($selectedIntegration['owner_email'] ?? '') ?></span>
                  </div>
                  <div class="im-admin-productos-meta__item">
                    <span>Dominio autorizado</span>
                    <code><?= $h($selectedIntegration['allowed_domain'] ?? '') ?></code>
                  </div>
                  <div class="im-admin-productos-meta__item">
                    <span>Clave publica</span>
                    <code><?= $h($selectedIntegration['public_key'] ?? '') ?></code>
                  </div>
                </div>
              <?php else: ?>
                <div class="im-alerta im-alerta--info">Todavia no hay integraciones con usuario propietario asignado.</div>
              <?php endif; ?>
            </article>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3><?= $productCurrent ? 'Editar producto' : 'Nuevo producto' ?></h3>
                  <p>El producto quedara asociado al usuario propietario de la integracion seleccionada.</p>
                </div>
              </div>

              <?php if ($selectedIntegration === null): ?>
                <div class="im-alerta im-alerta--info">Necesitas una integracion con usuario asignado para crear productos desde administracion.</div>
              <?php else: ?>
                <form method="post" enctype="multipart/form-data" class="im-formulario" data-quill-form>
                  <input type="hidden" name="admin_product_action" value="save">
                  <input type="hidden" name="item_id" value="<?= (int) ($productCurrent['id'] ?? 0) ?>">
                  <input type="hidden" name="api_integration_id" value="<?= (int) ($selectedIntegration['id'] ?? 0) ?>">
                  <input type="hidden" name="integration_id_redirect" value="<?= (int) ($selectedIntegration['id'] ?? 0) ?>">
                  <input type="hidden" name="description_html" value="<?= $h($descriptionValue) ?>" data-quill-hidden>
                  <input type="hidden" name="slug" value="<?= $h($productCurrent['slug'] ?? '') ?>">

                  <label class="im-campo im-campo-material im-campo--ancho">
                    <span>Se asigna a</span>
                    <input type="text" value="<?= $h(($selectedIntegration['owner_name'] ?? '') . ' - ' . ($selectedIntegration['owner_email'] ?? '')) ?>" readonly>
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Titulo</span>
                    <input type="text" name="title" maxlength="180" value="<?= $h($productCurrent['title'] ?? '') ?>" required>
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>SKU</span>
                    <input type="text" name="sku" maxlength="80" value="<?= $h($productCurrent['sku'] ?? '') ?>">
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Estado</span>
                    <select name="status">
                      <?php foreach ($statusOptions as $statusValue => $statusLabel): ?>
                        <option value="<?= $h($statusValue) ?>" <?= ($productCurrent['status'] ?? 'draft') === $statusValue ? 'selected' : '' ?>><?= $h($statusLabel) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Categoria</span>
                    <input type="text" name="category" maxlength="120" value="<?= $h($productCurrent['category'] ?? '') ?>">
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Subcategoria</span>
                    <input type="text" name="subcategory" maxlength="120" value="<?= $h($productCurrent['subcategory'] ?? '') ?>">
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Precio</span>
                    <input type="number" step="0.01" name="price" value="<?= $h($productCurrent['price'] ?? '') ?>">
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Moneda</span>
                    <input type="text" name="currency" maxlength="8" value="<?= $h($productCurrent['currency'] ?? 'ARS') ?>">
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Stock</span>
                    <input type="number" min="0" name="stock_quantity" value="<?= $h($productCurrent['stock_quantity'] ?? '') ?>">
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Disponibilidad</span>
                    <select name="availability">
                      <?php foreach ($availabilityOptions as $availabilityValue => $availabilityLabel): ?>
                        <option value="<?= $h($availabilityValue) ?>" <?= ($productCurrent['availability'] ?? 'on_request') === $availabilityValue ? 'selected' : '' ?>><?= $h($availabilityLabel) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </label>
                  <label class="im-campo im-campo-material">
                    <span>Orden</span>
                    <input type="number" min="1" name="sort_order" value="<?= (int) ($productCurrent['sort_order'] ?? 1) ?>">
                  </label>
                  <label class="im-campo im-campo-material im-campo--ancho">
                    <span>Descripcion corta</span>
                    <textarea name="short_description" rows="3" maxlength="300"><?= $h($productCurrent['short_description'] ?? '') ?></textarea>
                  </label>
                  <label class="im-campo im-campo-material im-campo--ancho">
                    <span><input type="checkbox" name="featured" value="1" <?= !empty($productCurrent['featured']) ? 'checked' : '' ?>> Destacar producto</span>
                  </label>

                  <div class="im-campo im-campo--ancho">
                    <span>Descripcion enriquecida</span>
                    <div class="im-muestra" data-quill-editor style="min-height:220px;"><?= $descriptionValue ?></div>
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

                  <?php if (($productCurrent['main_image_path'] ?? '') !== '' || ($productCurrent['thumbnail_path'] ?? '') !== '' || ($productCurrent['attachment_path'] ?? '') !== ''): ?>
                    <div class="im-admin-productos-archivos im-campo--ancho">
                      <?php if (($productCurrent['main_image_path'] ?? '') !== ''): ?>
                        <div class="im-admin-productos-archivo">Imagen principal actual: <strong><?= $h($basename($productCurrent['main_image_path'] ?? '')) ?></strong></div>
                      <?php endif; ?>
                      <?php if (($productCurrent['thumbnail_path'] ?? '') !== ''): ?>
                        <div class="im-admin-productos-archivo">Miniatura actual: <strong><?= $h($basename($productCurrent['thumbnail_path'] ?? '')) ?></strong></div>
                      <?php endif; ?>
                      <?php if (($productCurrent['attachment_path'] ?? '') !== ''): ?>
                        <div class="im-admin-productos-archivo">Adjunto actual: <strong><?= $h($basename($productCurrent['attachment_path'] ?? '')) ?></strong></div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                  <p class="im-admin-productos-note im-campo--ancho">El slug se genera automaticamente. El precio comparativo y metadata quedan fuera del formulario porque no son necesarios para esta carga administrativa inicial.</p>

                  <div class="im-formulario__acciones">
                    <?php if ($productCurrent): ?>
                      <a class="im-boton im-boton--texto" href="?integration_id=<?= (int) ($selectedIntegration['id'] ?? 0) ?>">Nuevo producto</a>
                      <button class="im-boton" type="submit" name="admin_product_action" value="deactivate" formnovalidate>Desactivar</button>
                    <?php endif; ?>
                    <button class="im-boton im-boton--principal" type="submit">Guardar producto</button>
                  </div>
                </form>
              <?php endif; ?>
            </article>
          </div>

          <article class="im-tabla-tareas__tarjeta">
            <div class="im-tabla-tareas__cabecera">
              <div>
                <h3>Catalogo cargado</h3>
                <p>Listado de productos para la integracion seleccionada.</p>
              </div>
            </div>
            <?php if ($selectedIntegration === null): ?>
              <div class="im-alerta im-alerta--info">Selecciona primero una integracion para ver y administrar productos.</div>
            <?php else: ?>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>Titulo</th>
                      <th>SKU</th>
                      <th>Estado</th>
                      <th>Disponibilidad</th>
                      <th>Precio</th>
                      <th>Destacado</th>
                      <th>Actualizado</th>
                      <th class="im-tabla-tareas__acciones">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($productos as $product): ?>
                      <tr>
                        <td class="im-tabla-tareas__nombre"><?= $h($product['title'] ?? '') ?></td>
                        <td><code><?= $h($product['sku'] ?? '-') ?></code></td>
                        <td><span class="im-chip <?= ($product['status'] ?? 'draft') === 'active' ? 'im-chip--completado' : (($product['status'] ?? 'draft') === 'draft' ? 'im-chip--pendiente' : 'im-chip--alerta') ?>"><?= $h($statusOptions[$product['status'] ?? 'draft'] ?? ($product['status'] ?? 'draft')) ?></span></td>
                        <td><?= $h($availabilityOptions[$product['availability'] ?? 'on_request'] ?? ($product['availability'] ?? 'on_request')) ?></td>
                        <td><?= $h($formatMoney($product['price'] ?? null, (string) ($product['currency'] ?? 'ARS'))) ?></td>
                        <td><?= !empty($product['featured']) ? 'Si' : 'No' ?></td>
                        <td><?= $h($formatDate($product['updated_at'] ?? null)) ?></td>
                        <td class="im-tabla-tareas__acciones">
                          <div class="im-chip-lista">
                            <a class="im-boton im-boton--texto" href="?integration_id=<?= (int) ($selectedIntegration['id'] ?? 0) ?>&edit_id=<?= (int) ($product['id'] ?? 0) ?>">Editar</a>
                            <form method="post">
                              <input type="hidden" name="admin_product_action" value="toggle_status">
                              <input type="hidden" name="item_id" value="<?= (int) ($product['id'] ?? 0) ?>">
                              <input type="hidden" name="api_integration_id" value="<?= (int) ($selectedIntegration['id'] ?? 0) ?>">
                              <input type="hidden" name="integration_id_redirect" value="<?= (int) ($selectedIntegration['id'] ?? 0) ?>">
                              <input type="hidden" name="target_status" value="<?= ($product['status'] ?? 'draft') === 'active' ? 'inactive' : 'active' ?>">
                              <button class="im-boton im-boton--texto" type="submit"><?= ($product['status'] ?? 'draft') === 'active' ? 'Desactivar' : 'Activar' ?></button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <?php if ($productos === []): ?>
                      <tr><td colspan="8">Todavia no hay productos cargados para esta integracion.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </article>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialJsSrc(), ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script>
    document.querySelectorAll('[data-quill-form]').forEach((form) => {
      const editorNode = form.querySelector('[data-quill-editor]');
      const hidden = form.querySelector('[data-quill-hidden]');
      if (!editorNode || !hidden || typeof Quill === 'undefined' || form.dataset.quillInitialized === 'true') return;
      const quill = new Quill(editorNode, { theme: 'bubble' });
      quill.root.innerHTML = hidden.value || '<p></p>';
      form.dataset.quillInitialized = 'true';
      form.addEventListener('submit', () => { hidden.value = quill.root.innerHTML; });
    });
  </script>
</body>
</html>
