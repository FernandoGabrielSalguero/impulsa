Vamos a ajustar el módulo de marketing ya implementado. No rehagas todo desde cero: trabajá sobre lo existente, revisá primero los archivos actuales y mantené el patrón MVC, roles, requires, CSS/JS externos y estética del CDN.

Objetivo principal:
Mejorar el Constructor de planes para que el foco sea crear un plan nuevo de forma clara, cómoda y completa. La edición de planes existentes debe hacerse desde un modal de selección/previsualización.

Reglas generales:
- No usar CSS inline.
- Usar clases/componentes del CDN y la copia local assets/impulsa_material/index.html.
- Si hace falta CSS nuevo, usar archivo CSS separado siguiendo la estructura actual.
- Si hace falta JS nuevo, usar archivo JS separado siguiendo la estructura actual.
- Mantener la lógica encapsulada en los componentes compartidos, no en los módulos por rol.
- No romper admin/marketing/emprendedor/cliente.
- Verificar sintaxis PHP y flujos principales al final.

Cambios en Constructor de planes:

1. Unificar layout:
Actualmente el constructor está dividido en 3 tarjetas: Editar plan, Ítems incluidos y Opciones de precio.
Quiero que sea una sola experiencia/formulario, no 3 tarjetas separadas.
Debe permitir:
- crear un plan nuevo
- agregar múltiples ítems incluidos y verlos todos en pantalla
- agregar opciones de precio/duración y verlas todas en pantalla
- actualizar un plan existente cuando se precargue desde el modal

2. Tarjeta/Formulario principal:
El bloque puede seguir llamándose “Crear plan” o “Editar plan” según haya o no un plan cargado.
Debe incluir todos los campos relevantes del plan, ítems y precios en una sola vista ordenada.

3. Campos desplegables:
En la sección del plan, cambiar estos campos a select:
- Objetivo
- Frecuencia de reporte
- Nivel de soporte
- Periodo de cobro

Generá opciones básicas y útiles.

Opciones sugeridas para Objetivo:
- Ganar visibilidad
- Generar consultas
- Aumentar ventas
- Captar leads
- Fidelizar clientes
- Posicionar marca

Opciones sugeridas para Frecuencia de reporte:
- Semanal
- Quincenal
- Mensual
- Bimestral
- Trimestral
- Al finalizar campaña

Opciones sugeridas para Nivel de soporte:
- Básico
- Estándar
- Prioritario
- Estratégico
- Premium
- A medida

Opciones sugeridas para Periodo de cobro:
- Mensual
- Bimestral
- Trimestral
- Semestral
- Anual
- Pago único

Aclaración:
Los campos “Setup fee” y “Periodo de cobro” no están claros para el usuario final. Agregar microcopy/tooltips breves:
- Setup fee: “Costo inicial de configuración del plan. Usar 0 si no aplica.”
- Periodo de cobro: “Frecuencia con la que se cobrará el plan cuando se active el sistema de pagos.”

4. Ítems incluidos:
Cambiar el label del campo “Item” por “Nombre”.
Cambiar el campo “Unidad” a select con estas 9 opciones:
- unidad
- publicación
- historia
- reel
- campaña
- anuncio
- hora
- reunión
- informe

Los ítems deben poder agregarse dinámicamente, verse en una lista dentro del mismo formulario y poder editarse/eliminarse antes de guardar.

5. Opciones de precio:
La sección actual no se entiende bien. Rediseñarla con labels y microcopy más claros.
Debe explicar que una opción de precio representa una alternativa comercial del mismo plan, por ejemplo:
- 1 mes
- 3 meses
- 6 meses
- 12 meses

Cambiar el título visual a algo más claro, por ejemplo:
“Precios y duración del plan”

Campos con labels claros:
- Duración en meses
- Precio mensual
- Precio total
- Costo inicial
- Moneda
- Opción destacada
- Opción predeterminada

Agregar microcopy:
“Podés crear varias opciones para el mismo plan, por ejemplo mensual, trimestral o anual.”

Las opciones de precio deben poder agregarse dinámicamente, verse en una lista dentro del mismo formulario y poder editarse/eliminarse antes de guardar.

6. Modal de planes existentes:
Los planes ya creados no deben aparecer como tarjeta fija al costado del formulario.
En la cabecera, al lado del texto “Planes de marketing” y arriba/cerca del contador “1 planes”, agregar un ícono/botón visible para abrir un modal de planes existentes.

Comportamiento del modal:
- Al presionar el ícono, abrir modal.
- El modal muestra tarjetas de planes existentes.
- Las tarjetas deben previsualizar cómo lo ven clientes y emprendedores: nombre, descripción corta, estado, ítems principales, precios/duración y badge de publicado/borrador/pausado.
- Al hacer click en un plan, cerrar modal y precargar sus datos en el formulario principal para actualizarlo.
- Debe quedar claro si el formulario está creando un plan nuevo o editando uno existente.
- Agregar botón “Nuevo plan” para limpiar el formulario y volver al modo creación.

7. Eliminación y error de foreign key:
Al intentar eliminar una opción de precio usada por una suscripción aparece este error:
SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails (`u104036906_impulsaGroup`.`marketing_plan_subscriptions`, CONSTRAINT `fk_marketing_subscriptions_pricing` FOREIGN KEY (`pricing_option_id`) REFERENCES `marketing_plan_pricing_options` (`id`))

Corregir este flujo.
No permitir eliminar físicamente un plan, feature u opción de precio si está referenciado por suscripciones u otras tablas relacionadas.
En esos casos:
- mostrar un mensaje amigable
- para planes, sugerir pausar o archivar
- para opciones de precio, impedir eliminación si ya fue usada en una suscripción
- para ítems, revisar si tiene referencias; si no tiene, permitir eliminar

Si la tabla no tiene soft delete para pricing/features, no inventar columnas. Validar dependencias antes de borrar y responder con error controlado.

8. UX esperada:
- El constructor debe sentirse como un formulario único de creación/edición de plan.
- Los planes existentes viven en modal, no ocupan espacio permanente.
- Ítems y precios deben agregarse de forma clara y visible.
- Mantener diseño elegante, responsive y consistente con el CDN.
- No crear datos demo.

Archivos esperados:
Trabajar principalmente en el componente compartido:
impulsa_emprende/partials/marketing/constructor de planes/constructorPlanesMarketingView.php
impulsa_emprende/partials/marketing/constructor de planes/constructorPlanesMarketingModel.php
impulsa_emprende/partials/marketing/constructor de planes/constructorPlanesMarketingController.php

También actualizar CSS/JS del módulo si corresponde, respetando archivos externos y sin CSS inline.

Al finalizar:
- Resumí archivos modificados.
- Explicá cómo quedó el nuevo flujo.
- Indicá qué verificaciones hiciste.