Vamos a agregar nuevas funcionalidades sobre el proyecto existente. No rehagas módulos desde cero: inspeccioná primero cómo están resueltos los patrones actuales de MVC, sesión, roles, conexión a BBDD, Mail.php, templates de correo, carga de CSS/JS y uso del CDN.

Antes de tocar código:
1. Revisá la estructura de BBDD en estructure/copiaLocalEstructuraBBDD.md.
2. Revisá el CDN público https://impulsagroup.com/assets/impulsa_material/index.html y la copia local assets/impulsa_material/index.html.
3. Revisá impulsa_emprende/mail/Mail.php para entender cómo se envían correos y cómo se registran en correos_log.
4. No uses CSS inline. Usá clases/componentes del CDN.
5. Usá queries preparadas y replicá los patrones existentes de seguridad/sanitización.

Tareas:

1. Enviar correo al equipo de marketing al solicitar un plan
Cuando un usuario con rol impulsa_emprendedor o impulsa_cliente presione “Solicitar plan”:
- mantener el guardado actual en BBDD
- después de guardar correctamente la solicitud, enviar un correo al usuario con rol impulsa_marketing
- siempre existe un solo usuario con rol impulsa_marketing
- obtener su email desde user_auth
- usar la lógica existente de impulsa_emprende/mail/Mail.php
- usar el template:
  impulsa_emprende/mail/template/solicitud_marketing.html
- completar ese template con datos útiles de la solicitud: usuario solicitante, rol, plan, opción de precio/duración, fecha, estado inicial y link/ruta de acceso si el proyecto ya maneja links internos. Toda la información del plan por favor. 
- si falla el envío del correo, no romper la solicitud: guardar la solicitud igual y mostrar mensaje controlado

2. Alta manual de usuarios desde admin
En la vista:
impulsa_emprende/view/admin/adminListUserView.php

Agregar un botón visible:
“Dar de alta usuario”

Al presionarlo, abrir Bottom sheet con formulario para crear usuario nuevo usando todos los campos requeridos por la tabla user_auth. Revisar la tabla en la estructura local de BBDD y no inventar campos.

Requisitos:
- La contraseña debe generarse automáticamente, larga y segura.
- No pedirle al admin que escriba contraseña manualmente.
- El rol debe elegirse desde un select con las opciones reales del enum de user_auth:
  impulsa_administrador
  impulsa_colaborador
  impulsa_emprendedor
  impulsa_usuario
  impulsa_marketing
  impulsa_cliente
- Guardar el usuario en BBDD siguiendo el patrón actual de hash de contraseña del proyecto.
- Después de crear el usuario, enviarle un correo con sus credenciales y link de acceso.
- Usar la lógica existente de impulsa_emprende/mail/Mail.php.
- Si ya existe template para alta/credenciales, reutilizarlo. Si no existe, crear uno siguiendo el patrón de templates existente.
- Registrar el envío en correos_log si Mail.php ya lo hace.
- Mostrar mensajes de éxito/error claros.

3. Dashboard admin con conteo dinámico de usuarios por rol
En:
impulsa_emprende/view/admin/dashboard.php

Actualizar las tarjetas de usuarios para que muestren dinámicamente la cantidad de usuarios según los roles existentes en user_auth.
Revisar el modelo/controlador correspondiente del dashboard admin y agregar ahí la consulta, sin meter lógica SQL directamente en la vista si el proyecto usa MVC.
Debe mostrar conteos por rol de forma clara y consistente con el diseño actual.

4. Vista de correos enviados
Completar estos archivos:
impulsa_emprende/view/admin/adminCorreosEnviadosView.php
impulsa_emprende/model/admin/adminMailModel.php
impulsa_emprende/controller/admin/adminMailController.php

Objetivo:
Generar una vista tipo tabla para listar correos enviados desde la tabla correos_log.

Columnas sugeridas:
- ID
- Usuario
- Correo
- Asunto
- Template
- Estado
- Fecha
- Acciones

Acciones:
Agregar una columna con ícono “...” o equivalente del CDN.
Al presionarlo, abrir un menú pequeño con:
- Ver correo
- Reenviar correo

Comportamiento:
- Ver correo: abrir dialog/modal mostrando el contenido del correo. Si existe mensaje_html, mostrar ese contenido; si no, mostrar mensaje_text. Mantenerlo legible y seguro.
- Reenviar correo: reenviar el correo al destinatario usando Mail.php y los datos guardados en correos_log.
- Registrar el nuevo intento de envío según el patrón de Mail.php/correos_log.
- Mostrar estado de éxito/error controlado.

UX/UI:
- Usar el CDN y la copia local para mantener estética.
- No usar CSS inline.
- Usar tabla responsive, badges para estados y menú de acciones prolijo.
- Mantener modales/dialogs consistentes con el resto del sistema.
- No romper vistas existentes.

Archivos principales a revisar/modificar:
- impulsa_emprende/mail/Mail.php
- impulsa_emprende/mail/template/solicitud_marketing.html
- impulsa_emprende/view/admin/adminListUserView.php
- controlador/modelo relacionado con adminListUserView.php
- impulsa_emprende/view/admin/dashboard.php
- modelo/controlador relacionado con dashboard.php
- impulsa_emprende/view/admin/adminCorreosEnviadosView.php
- impulsa_emprende/model/admin/adminMailModel.php
- impulsa_emprende/controller/admin/adminMailController.php
- componentes de visualizador/solicitud de planes de marketing donde esté implementado “Solicitar plan”

Agrega la página de Correos al menú existente en las páginas del usuario con rol administrador, que es quien la va a ver. De esta manera garantizamos el acceso a este nuevo modulo. 

Al finalizar:
- Resumí archivos modificados.
- Explicá cómo probar cada flujo.
- Verificá sintaxis PHP.
- Indicá cualquier supuesto tomado por falta de patrón claro.