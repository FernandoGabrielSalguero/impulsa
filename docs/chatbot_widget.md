# Chatbot Widget

## Tablas nuevas

- `chatbots`: un chatbot por `api_integration_id`, con estado, avatar, mensaje inicial, WhatsApp y bloqueo administrativo.
- `chatbot_nodes`: nodos/preguntas del flujo, con orden, estado y bandera de inicio.
- `chatbot_node_options`: botones por nodo con acciones `go_to_node`, `whatsapp`, `restart` y `close`.
- `chatbot_events`: metricas separadas del widget.

El SQL base esta en `estructure/chatbot.sql`.

## Asociacion con `api_integrations`

- La relacion principal es `chatbots.api_integration_id -> api_integrations.id`.
- Se mantiene la logica actual del proyecto para resolver accesos por usuario:
  - cliente: `projects.client_user_id` + coincidencia por `project_name`;
  - emprendedor: misma coincidencia por `project_name` y fallback a `landing_page_request.nombre_emprendimiento`.

Limitacion actual:
- Todavia no existe una FK explicita entre `api_integrations` y usuarios/proyectos. El filtro sigue el patron existente del repo para no romper compatibilidad.

## Administracion por usuario

- Cliente: `/impulsa_emprende/controller/client/ClienteChatbotController.php`
- Emprendedor: `/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php`

Ambas pantallas consumen el mismo componente compartido:

- `impulsa_emprende/partials/components/chatbot_builder/chatbot_builder_controller.php`
- `impulsa_emprende/partials/components/chatbot_builder/chatbot_builder_model.php`
- `impulsa_emprende/partials/components/chatbot_builder/chatbot_builder_view.php`
- `impulsa_emprende/partials/components/chatbot_builder/chatbot_builder.js`

## Administracion por admin

- Ruta: `/impulsa_emprende/controller/admin/adminChatbotController.php`

Permite:

- listar chatbots;
- ver dominio e integracion asociada;
- ver metricas globales por evento;
- desactivar/reactivar por administracion.

Cuando el admin bloquea un chatbot:

- `disabled_by_admin = 1`
- el estado publico queda fuera de servicio
- el usuario no puede reactivarlo desde su builder

## Endpoints publicos

### `GET /api/chatbot_config/index.php?public_key=...`

Devuelve:

- `success: true`
- `has_chatbot: false` si no existe chatbot activo
- `chatbot` completo si existe y esta disponible

Validaciones:

- `public_key`
- integracion existente y activa
- dominio permitido (`Origin` o `Referer`)
- chatbot activo y no bloqueado por admin

### `POST /api/chatbot_event/index.php`

Body JSON esperado:

```json
{
  "public_key": "pk_xxx",
  "chatbot_id": 1,
  "event_type": "widget_loaded",
  "node_id": 10,
  "option_id": 22,
  "page_url": "https://dominio.com/",
  "metadata": {
    "source": "widget"
  }
}
```

Eventos permitidos:

- `widget_loaded`
- `bubble_opened`
- `question_viewed`
- `option_clicked`
- `whatsapp_clicked`
- `chat_closed`

## Widget embebible

Archivo publico:

- `/api/chatbot_widget/widget.js`

Uso:

```html
<script src="https://impulsagroup.com/api/chatbot_widget/widget.js?key=PUBLIC_KEY"></script>
```

Comportamiento:

- lee la `public_key` desde el query string;
- consulta `chatbot_config`;
- no renderiza nada si `has_chatbot` es `false`;
- muestra burbuja flotante si hay chatbot activo;
- registra eventos contra `chatbot_event`.

## Decisiones tecnicas

- No hay IA.
- No hay input de texto libre.
- El flujo es estrictamente por botones.
- Las metricas del chatbot se guardan separadas de `visit_user_page`.
- El widget es JS puro, sin dependencias externas.
- El constructor interno reutiliza el sistema visual de `assets/impulsa_material`.

