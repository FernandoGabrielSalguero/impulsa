<?php
$datos = $datos ?? [];
$errores = $errores ?? [];
$exito = $exito ?? false;
$pageUrl = $pageUrl ?? '';
$csrfToken = $csrfToken ?? '';

function requestPageExternalValor(array $datos, string $campo): string
{
    return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}

function requestPageExternalChecked(array $datos, string $campo, string $valor): string
{
    return ($datos[$campo] ?? '') === $valor ? 'checked' : '';
}

function requestPageExternalArrayChecked(array $datos, string $campo, string $valor): string
{
    return in_array($valor, (array) ($datos[$campo] ?? []), true) ? 'checked' : '';
}

$referencias = array_values(array_filter((array) ($datos['referencias'] ?? []), 'is_string'));
$referencias = $referencias ?: [''];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Solicitar página web | Impulsa Group</title>
  <meta name="description" content="Formulario para solicitar el desarrollo de una página web a Impulsa Group.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://impulsagroup.com/assets/impulsa_material/css/material.css">
  <style>
    [data-request-stepper] .im-stepper__pasos { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    [data-request-stepper] .im-stepper__pasos button { min-width: 0; }
    .im-request-cerradas { grid-column: 1 / -1; }
    .im-request-referencias { display: grid; gap: .75rem; }
    @media (max-width: 640px) {
      [data-request-stepper] .im-stepper__pasos { gap: .35rem; }
      [data-request-stepper] .im-stepper__pasos button { padding: .55rem .2rem; font-size: .75rem; }
      [data-request-stepper] .im-stepper__pasos button span { font-size: .62rem; }
    }
  </style>
  <script src="https://impulsagroup.com/assets/impulsa_material/js/material.js" defer></script>
  <script src="https://impulsagroup.com/assets/impulsa_material/js/material-validaciones.js" defer></script>
</head>
<body>
  <main class="im-contenido">
    <section class="im-encabezado-seccion">
      <div>
        <p class="im-sobrelinea">Impulsa Group</p>
        <h1>Contanos sobre tu próxima página web</h1>
        <p>Completá este brief para que podamos entender el proyecto y preparar una propuesta.</p>
      </div>
    </section>

    <?php if ($exito): ?>
      <article class="im-tarjeta">
        <span class="im-chip im-chip--exito">Solicitud enviada</span>
        <h2>Recibimos tu información</h2>
        <p>Vamos a revisar el proyecto y nos comunicaremos con vos.</p>
      </article>
    <?php else: ?>
      <?php if ($errores): ?>
        <div class="im-alerta im-alerta--info" role="alert"><?= htmlspecialchars(implode(' ', $errores), ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <article class="im-tarjeta im-stepper" data-stepper data-request-stepper>
        <div class="im-stepper__pasos">
          <button class="activo" type="button">1<span>Contacto</span></button>
          <button type="button">2<span>Dirigida a</span></button>
          <button type="button">3<span>Estructura</span></button>
          <button type="button">4<span>Detalles</span></button>
        </div>
        <div class="im-stepper__contenido" aria-live="polite">Contanos cómo podemos comunicarnos con vos.</div>

        <form action="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data" data-request-form novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <input type="text" name="website" value="" tabindex="-1" autocomplete="off" hidden>

          <section class="im-formulario" data-request-panel>
            <label class="im-campo im-campo-material" data-im-campo="nombre">
              <span>Nombre y apellido</span>
              <input type="text" name="nombre_apellido" maxlength="150" value="<?= requestPageExternalValor($datos, 'nombre_apellido') ?>" required>
              <small data-im-error>Ingresá tu nombre y apellido.</small>
            </label>
            <label class="im-campo im-campo-material" data-im-campo="generico">
              <span>Nombre del proyecto</span>
              <input type="text" name="nombre_proyecto" maxlength="180" value="<?= requestPageExternalValor($datos, 'nombre_proyecto') ?>" required>
              <small data-im-error>Ingresá el nombre del proyecto.</small>
            </label>
            <label class="im-campo im-campo-material" data-im-campo="email">
              <span>Correo electrónico</span>
              <input type="email" name="correo_electronico" maxlength="190" value="<?= requestPageExternalValor($datos, 'correo_electronico') ?>" required>
              <small data-im-error>Ingresá un correo válido.</small>
            </label>
            <label class="im-campo im-campo-material" data-im-campo="whatsapp">
              <span>WhatsApp</span>
              <input type="tel" name="whatsapp" maxlength="80" value="<?= requestPageExternalValor($datos, 'whatsapp') ?>" required>
              <small data-im-error>Ingresá un WhatsApp válido.</small>
            </label>
          </section>

          <section class="im-formulario" data-request-panel hidden>
            <?php
            $pasoDos = [
                'actividad' => ['Actividad', '¿A qué te dedicás?'],
                'objetivo' => ['Objetivo', '¿Para qué querés la web?'],
                'publico' => ['Público', '¿A qué público estará dirigida?'],
                'accion_principal' => ['Acción principal', '¿Qué acción principal buscás que realice el visitante?'],
                'propuesta_destacar' => ['Propuesta a destacar', '¿Tenés algún servicio, producto o propuesta que quieras destacar?'],
            ];
            ?>
            <?php foreach ($pasoDos as $campo => [$titulo, $pregunta]): ?>
              <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
                <span><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></span>
                <textarea name="<?= $campo ?>" rows="4" placeholder="<?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?>" data-im-placeholder required><?= requestPageExternalValor($datos, $campo) ?></textarea>
                <small data-im-error>Completá este campo.</small>
                <em class="im-campo__contador" data-im-contador>0/5000</em>
              </label>
            <?php endforeach; ?>
          </section>

          <section class="im-formulario" data-request-panel hidden>
            <fieldset class="im-campo im-campo-grupo im-campo--ancho" data-request-checkbox-group>
              <legend>¿Qué secciones necesitan?</legend>
              <?php foreach (['Quiénes somos', 'Contacto', 'Inicio', 'Nuestra experiencia', 'Otro'] as $opcion): ?>
                <label class="im-checkbox"><input type="checkbox" name="secciones[]" value="<?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>" <?= requestPageExternalArrayChecked($datos, 'secciones', $opcion) ?>><span><?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?></span></label>
              <?php endforeach; ?>
              <small>Seleccioná al menos una sección.</small>
            </fieldset>

            <?php
            $cerradasPasoTres = [
                'textos_armados' => '¿Ya tienen los textos preparados?',
                'material_marca' => '¿Tienen manual de marca o lineamientos visuales?',
                'recursos_visuales' => '¿Tienen fotos o videos propios?',
            ];
            ?>
            <div class="im-grilla im-grilla--tres-columnas im-request-cerradas">
              <?php foreach ($cerradasPasoTres as $campo => $pregunta): ?>
                <fieldset class="im-campo im-campo-grupo" data-im-campo="radio">
                  <legend><?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?></legend>
                  <div class="im-radio-grupo">
                    <label class="im-radio"><input type="radio" name="<?= $campo ?>" value="Sí" <?= requestPageExternalChecked($datos, $campo, 'Sí') ?> required><span>Sí</span></label>
                    <label class="im-radio"><input type="radio" name="<?= $campo ?>" value="No" <?= requestPageExternalChecked($datos, $campo, 'No') ?> required><span>No</span></label>
                  </div>
                  <small data-im-error>Seleccioná una opción.</small>
                </fieldset>
              <?php endforeach; ?>
            </div>

            <fieldset class="im-campo im-campo-grupo im-campo--ancho" data-request-checkbox-group>
              <legend>¿Cómo les gustaría que se contacten los usuarios?</legend>
              <?php foreach (['Formulario en la web', 'Redes sociales', 'WhatsApp Business', 'Otro'] as $opcion): ?>
                <label class="im-checkbox"><input type="checkbox" name="contacto_usuarios[]" value="<?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>" <?= requestPageExternalArrayChecked($datos, 'contacto_usuarios', $opcion) ?>><span><?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?></span></label>
              <?php endforeach; ?>
              <small>Seleccioná al menos una opción.</small>
            </fieldset>

            <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
              <span>Estilo visual</span>
              <textarea name="estilo_visual" rows="4" placeholder="Describí cómo te gustaría que se vea la web." data-im-placeholder required><?= requestPageExternalValor($datos, 'estilo_visual') ?></textarea>
              <small data-im-error>Completá este campo.</small>
              <em class="im-campo__contador" data-im-contador>0/5000</em>
            </label>

            <fieldset class="im-campo im-campo-grupo im-campo--ancho">
              <legend>Referencias de sitios que te gusten</legend>
              <div class="im-request-referencias" data-request-referencias>
                <?php foreach ($referencias as $referencia): ?>
                  <label class="im-campo im-campo-material">
                    <span>URL de referencia</span>
                    <input type="url" name="referencias[]" value="<?= htmlspecialchars($referencia, ENT_QUOTES, 'UTF-8') ?>" placeholder="https://ejemplo.com" required>
                    <small>Ingresá una URL válida.</small>
                  </label>
                <?php endforeach; ?>
              </div>
              <button class="im-boton im-boton--tonal" type="button" data-request-agregar-url>Agregar otra URL</button>
            </fieldset>

            <label class="im-campo im-campo-material im-campo--ancho">
              <span>Imágenes de apoyo</span>
              <input type="file" name="imagenes_apoyo[]" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" multiple>
              <small>Podés adjuntar hasta 10 archivos de 10 MB cada uno.</small>
            </label>
          </section>

          <section class="im-formulario" data-request-panel hidden>
            <?php
            $cerradasPasoCuatro = [
                'tiene_dominio' => '¿Ya tienen dominio?',
                'tiene_hosting' => '¿Ya tienen hosting?',
                'necesita_correos_institucionales' => '¿Necesitan correos institucionales?',
            ];
            ?>
            <div class="im-grilla im-grilla--tres-columnas im-request-cerradas">
              <?php foreach ($cerradasPasoCuatro as $campo => $pregunta): ?>
                <fieldset class="im-campo im-campo-grupo" data-im-campo="radio">
                  <legend><?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?></legend>
                  <div class="im-radio-grupo">
                    <label class="im-radio"><input type="radio" name="<?= $campo ?>" value="Sí" <?= requestPageExternalChecked($datos, $campo, 'Sí') ?> required><span>Sí</span></label>
                    <label class="im-radio"><input type="radio" name="<?= $campo ?>" value="No" <?= requestPageExternalChecked($datos, $campo, 'No') ?> required><span>No</span></label>
                  </div>
                  <small data-im-error>Seleccioná una opción.</small>
                </fieldset>
              <?php endforeach; ?>
            </div>

            <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
              <span>Comentarios adicionales</span>
              <textarea name="comentarios_adicionales" rows="4" placeholder="¿Hay algo más que quieras compartir?" data-im-placeholder required><?= requestPageExternalValor($datos, 'comentarios_adicionales') ?></textarea>
              <small data-im-error>Completá este campo.</small>
              <em class="im-campo__contador" data-im-contador>0/5000</em>
            </label>
            <div class="im-formulario__acciones"><button class="im-boton im-boton--principal" type="submit">Enviar solicitud</button></div>
          </section>
        </form>

        <div class="im-muestra">
          <button class="im-boton im-boton--texto" type="button" data-stepper-prev>Anterior</button>
          <button class="im-boton im-boton--principal" type="button" data-stepper-next>Siguiente</button>
        </div>
      </article>
    <?php endif; ?>
  </main>

  <template data-request-url-template>
    <label class="im-campo im-campo-material">
      <span>URL de referencia</span>
      <input type="url" name="referencias[]" placeholder="https://ejemplo.com" required>
      <small>Ingresá una URL válida.</small>
    </label>
  </template>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const stepper = document.querySelector("[data-request-stepper]");
      if (!stepper) return;

      const pasos = [...stepper.querySelectorAll(".im-stepper__pasos button")];
      const paneles = [...stepper.querySelectorAll("[data-request-panel]")];
      const estado = stepper.querySelector(".im-stepper__contenido");
      const anterior = stepper.querySelector("[data-stepper-prev]");
      const siguiente = stepper.querySelector("[data-stepper-next]");
      const formulario = stepper.querySelector("[data-request-form]");
      const mensajes = [
        "Contanos cómo podemos comunicarnos con vos.",
        "Contanos más detalladamente a qué te dedicás, cuál es el objetivo de la página web y a quién está dirigida.",
        "Revisemos algunos detalles sobre el contenido de tu página web:",
        "Últimos detalles técnicos",
      ];
      let indice = 0;

      const mostrarPaso = (nuevoIndice) => {
        indice = Math.max(0, Math.min(paneles.length - 1, nuevoIndice));
        pasos.forEach((paso, posicion) => paso.classList.toggle("activo", posicion === indice));
        paneles.forEach((panel, posicion) => panel.hidden = posicion !== indice);
        estado.textContent = mensajes[indice];
        anterior.hidden = indice === 0;
        siguiente.hidden = indice === paneles.length - 1;
      };

      const prepararValidaciones = (panel) => {
        panel.querySelectorAll("[data-request-checkbox-group]").forEach((grupo) => {
          const checks = [...grupo.querySelectorAll('input[type="checkbox"]')];
          const mensaje = checks.some((check) => check.checked) ? "" : "Seleccioná al menos una opción.";
          checks[0]?.setCustomValidity(mensaje);
        });
        const archivos = panel.querySelector('input[type="file"][name="imagenes_apoyo[]"]');
        archivos?.setCustomValidity(archivos.files.length > 10 ? "Podés adjuntar hasta 10 archivos." : "");
      };

      const validarPaso = () => {
        prepararValidaciones(paneles[indice]);
        const invalido = [...paneles[indice].querySelectorAll("input, textarea, select")].find((control) => !control.checkValidity());
        pasos[indice].classList.toggle("error", Boolean(invalido));
        invalido?.reportValidity();
        invalido?.focus();
        return !invalido;
      };

      stepper.addEventListener("click", (evento) => {
        const botonPaso = evento.target.closest(".im-stepper__pasos button");
        const botonAnterior = evento.target.closest("[data-stepper-prev]");
        const botonSiguiente = evento.target.closest("[data-stepper-next]");
        if (!botonPaso && !botonAnterior && !botonSiguiente) return;
        evento.preventDefault();
        evento.stopPropagation();

        if (botonAnterior) return mostrarPaso(indice - 1);
        if (botonSiguiente) return validarPaso() && mostrarPaso(indice + 1);

        const destino = pasos.indexOf(botonPaso);
        if (destino <= indice || (destino === indice + 1 && validarPaso())) mostrarPaso(destino);
      }, true);

      formulario.addEventListener("submit", (evento) => {
        paneles.forEach(prepararValidaciones);
        if (formulario.checkValidity()) return;
        evento.preventDefault();
        const invalido = formulario.querySelector(":invalid");
        mostrarPaso(paneles.findIndex((panel) => panel.contains(invalido)));
        invalido?.reportValidity();
      });

      document.querySelector("[data-request-agregar-url]")?.addEventListener("click", () => {
        const template = document.querySelector("[data-request-url-template]");
        document.querySelector("[data-request-referencias]")?.append(template.content.cloneNode(true));
      });

      formulario.addEventListener("change", (evento) => {
        const grupo = evento.target.closest("[data-request-checkbox-group]");
        if (grupo) prepararValidaciones(grupo.closest("[data-request-panel]"));
      });

      mostrarPaso(0);
    });
  </script>
</body>
</html>
