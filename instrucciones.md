Hola Codex. Necesito implementar dos módulos/API nuevos en este proyecto, siguiendo la arquitectura real existente.

Contexto importante:
- Las APIs públicas actuales usan `api/_shared/api_integration_helpers.php`.
- Ese helper maneja `.env`, conexión PDO, CORS, validación de `public_key`, validación de origin, secret opcional, `apiResponderJson()` y `apiRegistrarUltimoUsoIntegracion()`.
- La pantalla admin de integraciones está en:
  `impulsa_emprende/view/admin/adminAPIconfigurationView.php`
- El controlador/modelo admin están en:
  `impulsa_emprende/controller/admin/adminAPIconfigurationController.php`
  `impulsa_emprende/model/admin/adminAPIconfigurationModel.php`
- El patrón de componente compartido reutilizable está en:
  `impulsa_emprende/partials/components/chatbot_builder/`
- Las vistas de cliente/emprendedor arman su propio menú lateral dentro de cada View.

Objetivo:
Crear dos APIs/módulos genéricos:
- Blog
- Productos

Rutas públicas API a crear:
- `api/blog_api/index.php`
- `api/producto_api/index.php`

Estas APIs deben seguir el mismo patrón que `api/contact_form_landing_page/index.php`:
- `declare(strict_types=1);`
- `require_once __DIR__ . '/../_shared/api_integration_helpers.php';`
- cargar env
- configurar CORS
- responder OPTIONS
- validar integración con `public_key`
- responder JSON con `apiResponderJson()`
- registrar último uso con `apiRegistrarUltimoUsoIntegracion()`
- usar PDO preparado
- validar entradas
- no inventar otro sistema de autenticación

Endpoints mínimos:
Para ambas APIs permitir:
- listar registros activos por `public_key`
- obtener detalle por id o slug
- crear
- editar
- desactivar/eliminar lógicamente
- subir archivo/imagen cuando corresponda

Si el proyecto no tiene router REST, resolver con `action` en JSON/FormData, por ejemplo:
- `list`
- `detail`
- `create`
- `update`
- `toggle_status`
- `delete`

Archivos de componentes compartidos:
Usar estos archivos para implementar componentes reutilizables, al estilo de `partials/components/chatbot_builder`:

Blog:
- `impulsa_emprende/partials/api_blog/api_blogController.php`
- `impulsa_emprende/partials/api_blog/api_blogModel.php`
- `impulsa_emprende/partials/api_blog/api_blogView.php`

Producto:
- `impulsa_emprende/partials/api_product/api_productoController.php`
- `impulsa_emprende/partials/api_product/api_productoModel.php`
- `impulsa_emprende/partials/api_product/api_productoView.php`

El controlador compartido debe recibir contexto desde cada vista, parecido a `$chatbotBuilderContext`, por ejemplo:
- usuario autenticado
- rol
- título
- descripción
- ruta de volver
- flash key
- action POST
- tipo de módulo

Crear también los controladores de página si no existen:
- `impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php`
- `impulsa_emprende/controller/emprendedor/EmprendedorProductController.php`
- `impulsa_emprende/controller/client/ClienteBlogController.php`
- `impulsa_emprende/controller/client/ClienteProductController.php`

Crear o completar las vistas:
- `impulsa_emprende/view/emprendedor/EmprendedorBlogView.php`
- `impulsa_emprende/view/emprendedor/EmprendedorProductView.php`
- `impulsa_emprende/view/client/ClienteBlogView.php`
- `impulsa_emprende/view/client/ClienteProductView.php`

Cada vista debe:
- requerir el rol correcto con `authRequiereRol()`
- cargar perfil con `partials/bottom_sheet_perfil/perfilController.php`
- usar `/assets/impulsa_material/css/material.css`
- usar `/assets/impulsa_material/js/material.js`
- incluir `partials/bottom_sheet_perfil/perfilView.php`
- renderizar el componente compartido correspondiente
- mantener el mismo estilo visual que `ClienteChatbotView.php` y `EmprendedorChatbotView.php`

Menú lateral:
Agregar accesos visibles a Blog y Productos en las vistas de:
- Cliente dashboard
- Cliente chatbot
- Cliente blog
- Cliente productos
- Emprendedor dashboard
- Emprendedor chatbot
- Emprendedor blog
- Emprendedor productos

Rutas sugeridas:
- `/impulsa_emprende/controller/client/ClienteBlogController.php`
- `/impulsa_emprende/controller/client/ClienteProductController.php`
- `/impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php`
- `/impulsa_emprende/controller/emprendedor/EmprendedorProductController.php`

Base de datos:
Revisar primero `estructure/copiaLocalEstructuraBBDD.md`.

Crear SQL para estas tablas, respetando estilo actual:
- ids unsigned
- `api_integration_id` como FK a `api_integrations.id`
- `created_by_user_id` si aplica
- `created_at`
- `updated_at`
- `status` con enum `active/inactive` o equivalente
- índices por integración, estado, slug y fechas

Tabla sugerida `api_blog_posts`:
- id BIGINT UNSIGNED PK AI
- api_integration_id BIGINT UNSIGNED NOT NULL
- title VARCHAR(180) NOT NULL
- slug VARCHAR(220) NOT NULL
- subtitle VARCHAR(255) NULL
- author VARCHAR(180) NULL
- bibliography TEXT NULL
- cover_image_path VARCHAR(255) NULL
- attachment_path VARCHAR(255) NULL
- category VARCHAR(120) NULL
- subcategory VARCHAR(120) NULL
- excerpt VARCHAR(300) NULL
- description_html LONGTEXT NOT NULL
- publication_date DATETIME NULL
- status ENUM('active','inactive','draft') NOT NULL DEFAULT 'draft'
- sort_order INT UNSIGNED NOT NULL DEFAULT 1
- metadata_json LONGTEXT NULL
- created_by_user_id INT UNSIGNED NULL
- created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
- UNIQUE(api_integration_id, slug)
- FK api_integration_id -> api_integrations.id
- FK created_by_user_id -> user_auth.id

Tabla sugerida `api_products`:
- id BIGINT UNSIGNED PK AI
- api_integration_id BIGINT UNSIGNED NOT NULL
- title VARCHAR(180) NOT NULL
- slug VARCHAR(220) NOT NULL
- sku VARCHAR(80) NULL
- short_description VARCHAR(300) NULL
- description_html LONGTEXT NOT NULL
- main_image_path VARCHAR(255) NULL
- thumbnail_path VARCHAR(255) NULL
- attachment_path VARCHAR(255) NULL
- category VARCHAR(120) NULL
- subcategory VARCHAR(120) NULL
- price DECIMAL(12,2) NULL
- compare_at_price DECIMAL(12,2) NULL
- currency VARCHAR(8) NOT NULL DEFAULT 'ARS'
- stock_quantity INT NULL
- availability ENUM('in_stock','out_of_stock','preorder','on_request') NOT NULL DEFAULT 'on_request'
- status ENUM('active','inactive','draft') NOT NULL DEFAULT 'draft'
- featured TINYINT(1) NOT NULL DEFAULT 0
- sort_order INT UNSIGNED NOT NULL DEFAULT 1
- metadata_json LONGTEXT NULL
- created_by_user_id INT UNSIGNED NULL
- created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
- UNIQUE(api_integration_id, slug)
- FK api_integration_id -> api_integrations.id
- FK created_by_user_id -> user_auth.id

Uploads:
Crear/usar carpetas:
- `impulsa_emprende/uploads/API_Blog`
- `impulsa_emprende/uploads/API_Productos`

Implementar validación de uploads similar a `chatbot_builder_controller.php`:
- crear carpeta si no existe
- validar `UPLOAD_ERR_OK`
- validar `is_uploaded_file`
- validar tamaño máximo
- validar extensión
- validar MIME con `finfo`
- generar nombre seguro con fecha + random bytes
- guardar path público usable por vistas/API

Tipos permitidos:
Blog:
- portada: jpg, jpeg, png, webp
- adjunto: pdf, doc, docx, xls, xlsx, csv, txt, zip si tiene sentido

Producto:
- foto/miniatura: jpg, jpeg, png, webp
- adjunto opcional: pdf u otros documentos seguros

Quill:
En los formularios de descripción de Blog y Producto usar Quill bubble:

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.bubble.css" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

Implementación esperada:
- usar un `<div>` editor visible
- usar un `<input type="hidden" name="description_html">`
- antes de submit copiar `quill.root.innerHTML` al hidden
- al editar, precargar HTML existente
- sanitizar/validar del lado servidor antes de guardar, permitiendo HTML básico necesario para texto enriquecido

Asociación con integraciones:
Reutilizar la lógica de accesibilidad de `ChatbotBuilderModel::obtenerIntegracionesAccesibles()`:
- una integración es accesible si `api_integrations.project_name` coincide con un `projects.project_name` donde `projects.client_user_id = userId` y `client_visible = 1`
- o si coincide con `landing_page_request.nombre_emprendimiento` donde `landing_page_request.user_auth_id = userId`

No duplicar esta lógica de forma desordenada. Si conviene, extraer helper/model compartido reutilizable para Blog, Producto y futuro contenido API.

Admin:
Actualizar `adminAPIconfigurationView.php` para que en el modal de detalle también aparezcan snippets de Blog y Productos.

Snippets sugeridos:
- Blog list/detail usando `/api/blog_api/index.php`
- Producto list/detail usando `/api/producto_api/index.php`

Mantener los snippets actuales:
- visitas
- formulario
- chatbot

No romper el flujo actual de crear integración, editar dominio, regenerar public key/secret key y activar/desactivar.

API pública:
Las respuestas de listado deben devolver registros activos/published de la integración validada.
Incluir URLs completas o paths públicos para:
- imágenes
- miniaturas
- adjuntos

Seguridad:
- prepared statements siempre
- validar `public_key`
- validar origin con helper existente
- validar secret si corresponde mediante helper existente
- no exponer registros de otra integración
- no confiar en ids enviados por cliente sin filtrar por `api_integration_id`
- no permitir paths arbitrarios
- no borrar archivos físicamente salvo que el patrón del proyecto ya lo haga

Al terminar:
1. Mostrar resumen breve de archivos creados/modificados.
2. Mostrar SQL generado.
3. Explicar cómo probar:
   - crear integración desde admin
   - configurar Blog desde cliente
   - configurar Producto desde cliente
   - configurar Blog desde emprendedor
   - configurar Producto desde emprendedor
   - listar vía API pública con `public_key`
   - subir imagen/adjunto
   - validar que las URLs devueltas funcionen
4. Ejecutar verificación PHP básica, por ejemplo `php -l` sobre archivos nuevos/modificados.
5. No hacer refactors grandes fuera del alcance.