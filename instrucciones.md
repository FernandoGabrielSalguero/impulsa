Vamos a construir por etapas el módulo de venta y gestión de planes de marketing.

Antes de tocar código:
1. Revisá módulos existentes para entender patrón MVC, conexión a BBDD, sesiones/roles, requires, carga de CSS/JS, AJAX/controllers y estética.
2. Revisá el CDN público https://impulsagroup.com/assets/impulsa_material/index.html y la copia local assets/impulsa_material/index.html. Usá la copia local para inspección detallada y el CDN público como referencia de consumo real.
3. No uses CSS inline. Replicá el sistema visual mediante clases existentes del CDN y, solo si falta algo, CSS propio en archivo separado.
4. Revisá estructure/copiaLocalEstructuraBBDD.md y usá las tablas existentes de marketing. No inventes tablas.

Objetivo general:
Crear componentes compartidos encapsulados en impulsa_emprende/partials/marketing/ para que los módulos por rol solo hagan require y no contengan lógica propia.

Componentes compartidos:
- constructor de planes:
  impulsa_emprende/partials/marketing/constructor de planes/constructorPlanesMarketingView.php
  constructorPlanesMarketingModel.php
  constructorPlanesMarketingController.php

- visualizador de planes:
  impulsa_emprende/partials/marketing/visualizador de planes/visualizadorPlanesMarketingView.php
  visualizadorPlanesMarketingModel.php
  visualizadorPlanesMarketingController.php

- monitor de planes:
  impulsa_emprende/partials/marketing/monitor de planes/monitorPlanesMarketingView.php
  monitorPlanesMarketingModel.php
  monitorPlanesMarketingController.php

- visualizador de resultados:
  impulsa_emprende/partials/marketing/visualizador de resultados/visualizadorResultadosMarketingView.php
  visualizadorResultadosMarketingModel.php
  visualizadorResultadosMarketingController.php

Si conviene renombrar carpetas para quitar espacios, hacelo y actualizá todos los require.

Reglas de roles:
- impulsa_administrador e impulsa_marketing ven los 4 componentes.
- impulsa_emprendedor e impulsa_cliente ven solo visualizador de planes y visualizador de resultados.
- Investigá cómo se valida el rol en el proyecto. Probablemente sea con $_SESSION['rol']; replicá el patrón existente.

Módulos por rol:
- Admin:
  impulsa_emprende/view/admin/adminMarketingView.php
  impulsa_emprende/model/admin/adminMarketingModel.php
  impulsa_emprende/controller/admin/adminMarketingController.php

- Marketing:
  impulsa_emprende/view/marketing/marketingDashboardView.php
  impulsa_emprende/model/marketing/marketingDashboardModel.php
  impulsa_emprende/controller/marketing/marketingDashboardController.php

- Emprendedor:
  impulsa_emprende/view/emprendedor/EmprendedorMarketingView.php
  impulsa_emprende/model/emprendedor/EmprendedorMarketingModel.php
  impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php

- Cliente:
  impulsa_emprende/view/client/ClienteMarketingView.php
  impulsa_emprende/model/client/ClienteMarketingModel.php
  impulsa_emprende/controller/client/ClienteMarketingController.php

Funcionalidad:
1. Constructor de planes:
   CRUD completo de planes usando marketing_plans, marketing_plan_features y marketing_plan_pricing_options.
   Debe permitir crear el plan completo: datos base, features, opciones de precio/duración.
   Admin/marketing pueden ver draft, published, paused y archived.
   Moneda por defecto en BBDD: ARS. En interfaz puede mostrarse como ARG si el proyecto ya lo muestra así, pero guardar ARS.

2. Visualizador de planes:
   Mostrar planes publicados y visibles para clientes: status='published' e is_visible_to_clients=1.
   Diseño atractivo tipo cards/pricing, siguiendo el CDN.
   Emprendedor/cliente deben poder solicitar un plan.
   Al solicitar, crear registro en marketing_plan_subscriptions con status='requested' y datos del plan/precio seleccionado.

3. Monitor de planes:
   Mostrar usuarios con planes contratados, estado, duración, mes actual, fechas, plan y responsable.
   Permitir cambiar estados de suscripción: requested, meeting_scheduled, active, paused, completed, cancelled.
   Permitir cargar CSV de Meta usando:
   https://cdn.jsdelivr.net/npm/papaparse@5.4.1/papaparse.min.js
   Implementar preview + importación real a BBDD:
   marketing_import_batches
   marketing_import_rows
   marketing_campaign_metrics
   Match automático por nombre exacto de campaña contra campañas internas. Si no matchea, permitir asignación manual.

4. Visualizador de resultados:
   Mostrar métricas y reportes de campañas/planes.
   Emprendedor ve solo resultados donde entrepreneur_user_id sea el usuario logueado.
   Cliente ve solo resultados donde client_user_id sea el usuario logueado.
   Admin/marketing pueden filtrar todos.
   Usar marketing_campaigns, marketing_campaign_metrics, marketing_commercial_metrics y marketing_reports según corresponda.

UX/UI y estilos:
- Usar HTML, CSS, JS y PHP.
- Apoyarse en el CDN y en assets/impulsa_material/index.html.
- No usar CSS inline en atributos style="".
- No escribir estilos dentro del HTML salvo que el proyecto ya tenga ese patrón y sea inevitable.
- Priorizar clases, componentes y utilidades existentes del CDN.
- Antes de crear estilos nuevos, buscar si el CDN ya provee una clase/componente equivalente.
- Si hacen falta estilos propios del módulo, crear un archivo CSS dedicado para marketing, por ejemplo:
  assets/css/marketing/marketingPlanes.css
  o seguir la carpeta/nomenclatura CSS existente del proyecto.
- Cargar ese CSS desde las vistas/módulos correspondientes siguiendo el patrón actual del proyecto.
- Mantener clases prefijadas para evitar conflictos, por ejemplo:
  marketing-plan-card
  marketing-plan-builder
  marketing-results-panel
  marketing-monitor-table
- El JS también debe ir en archivo separado si el proyecto ya organiza scripts así. Evitar scripts inline grandes.
- Permitido usar atributos data-* para comportamiento JS.
- Diseño elegante, ordenado, responsive.
- Para admin/marketing, integrar los 4 componentes en una sola página usando tabs, modales, drawers, bottom sheets o patrones similares.
- Estados vacíos prolijos. No crear datos demo.
- Cuidar los ítems de los menú. No crees submenú en las páginas. Actualiza todos los archivos para agregar los nuevos modulos a las páginas ya existentes. 

Seguridad/calidad:
- Usar queries preparadas y sanitización siguiendo el patrón del proyecto.
- Implementar CSRF solo si el proyecto ya lo usa.
- No duplicar lógica en módulos por rol; encapsular en componentes compartidos.
- No romper rutas existentes.
- Verificar sintaxis PHP y flujos principales.

Trabajá por etapas:
Etapa 1: inspección del proyecto y plan técnico breve.
Etapa 2: estructura MVC compartida + integración por rol con requires.
Etapa 3: constructor CRUD de planes completo.
Etapa 4: visualizador de planes + solicitud de plan.
Etapa 5: monitor de planes + cambio de estados + carga CSV Meta.
Etapa 6: visualizador de resultados.
Etapa 7: revisión visual, limpieza y verificación final.

Después de cada etapa, resumí qué hiciste, archivos modificados y qué queda pendiente antes de continuar.