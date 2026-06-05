<?php
$datos = $datos ?? [];
$errores = $errores ?? [];
$exito = $exito ?? false;
$pageUrl = $pageUrl ?? '';
$csrfToken = $csrfToken ?? '';

if (!function_exists('requestPageExternalValor')) {
    function requestPageExternalValor(array $datos, string $campo): string
    {
        return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('requestPageExternalChecked')) {
    function requestPageExternalChecked(array $datos, string $campo, string $valor): string
    {
        return ($datos[$campo] ?? '') === $valor ? 'checked' : '';
    }
}
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
        <div class="im-tarjeta__cabecera">
          <div>
            <span class="im-chip im-chip--exito">Solicitud enviada</span>
            <h2>Recibimos tu información</h2>
            <p>Vamos a revisar el proyecto y nos comunicaremos con vos.</p>
          </div>
        </div>
      </article>
    <?php else: ?>
      <?php if ($errores): ?>
        <div class="im-alerta im-alerta--info" role="alert">
          <?= htmlspecialchars(implode(' ', $errores), ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <article class="im-tarjeta im-stepper" data-stepper data-request-stepper>
        <div class="im-stepper__pasos">
          <button class="activo" type="button">1<span>Contacto</span></button>
          <button type="button">2<span>Dirigida a</span></button>
          <button type="button">3<span>Estructura</span></button>
          <button type="button">4<span>Detalles</span></button>
        </div>
        <div class="im-stepper__contenido" aria-live="polite">Paso 1 de 4: Datos de contacto</div>

        <form action="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data" data-request-form novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <input type="text" name="website" value="" tabindex="-1" autocomplete="off" hidden>

          <section class="im-formulario" data-request-panel>
            <div class="im-formulario__separador">Paso 1: Datos de contacto</div>
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
            <div class="im-formulario__separador">Paso 2: A quién está dirigida</div>
            <?php
            $pasoDos = [
                'actividad' => ['Actividad', '¿A qué se dedican?'],
                'objetivo' => ['Objetivo', '¿Para qué quieren la web?'],
                'publico' => ['Público', '¿A qué público estará dirigida?'],
                'accion_principal' => ['Acción principal', '¿Qué acción principal buscan que realice el visitante?'],
                'propuesta_destacar' => ['Propuesta a destacar', '¿Tienen algún servicio, producto o propuesta que quieran destacar?'],
            ];
            ?>
            <?php foreach ($pasoDos as $campo => [$titulo, $pregunta]): ?>
              <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
                <span><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></span>
                <textarea name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" rows="4" placeholder="<?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?>" data-im-placeholder required><?= requestPageExternalValor($datos, $campo) ?></textarea>
                <small data-im-error>Completá este campo.</small>
                <em class="im-campo__contador" data-im-contador>0/5000</em>
              </label>
            <?php endforeach; ?>
          </section>

          <section class="im-formulario" data-request-panel hidden>
            <div class="im-formulario__separador">Paso 3: Estructura de tu página web</div>
            <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
              <span>Secciones</span>
              <textarea name="secciones" rows="4" placeholder="¿Qué secciones necesitan? Por ejemplo: Quiénes somos, Servicios, Contacto, etc." data-im-placeholder required><?= requestPageExternalValor($datos, 'secciones') ?></textarea>
              <small data-im-error>Completá este campo.</small>
              <em class="im-campo__contador" data-im-contador>0/5000</em>
            </label>

            <?php
            $cerradasPasoTres = [
                'textos_armados' => '¿Ya tienen los textos preparados?',
                'material_marca' => '¿Tienen manual de marca o lineamientos visuales?',
                'recursos_visuales' => '¿Tienen fotos o videos propios?',
            ];
            ?>
            <?php foreach ($cerradasPasoTres as $campo => $pregunta): ?>
              <fieldset class="im-campo im-campo--ancho" data-im-campo="radio">
                <legend><?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?></legend>
                <div class="im-muestra">
                  <label><input type="radio" name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" value="Sí" <?= requestPageExternalChecked($datos, $campo, 'Sí') ?> required> Sí</label>
                  <label><input type="radio" name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" value="No" <?= requestPageExternalChecked($datos, $campo, 'No') ?> required> No</label>
                </div>
                <small data-im-error>Seleccioná una opción.</small>
              </fieldset>
            <?php endforeach; ?>

            <?php
            $abiertasPasoTres = [
                'contacto_usuarios' => ['Contacto de usuarios', '¿Cómo los van a contactar los usuarios?'],
                'estilo_visual' => ['Estilo visual', 'Describan cómo les gustaría que se vea la web.'],
                'referencias' => ['Referencias', 'Compartan links de páginas que les gusten.'],
            ];
            ?>
            <?php foreach ($abiertasPasoTres as $campo => [$titulo, $pregunta]): ?>
              <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
                <span><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></span>
                <textarea name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" rows="4" placeholder="<?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?>" data-im-placeholder required><?= requestPageExternalValor($datos, $campo) ?></textarea>
                <small data-im-error>Completá este campo.</small>
                <em class="im-campo__contador" data-im-contador>0/5000</em>
              </label>
            <?php endforeach; ?>

            <label class="im-campo im-campo-material im-campo--ancho">
              <span>Imágenes de apoyo</span>
              <input type="file" name="imagenes_apoyo[]" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" multiple>
              <small>Podés adjuntar hasta 10 archivos de 10 MB cada uno.</small>
            </label>
          </section>

          <section class="im-formulario" data-request-panel hidden>
            <div class="im-formulario__separador">Paso 4: Últimos detalles</div>
            <?php
            $cerradasPasoCuatro = [
                'tiene_dominio' => '¿Ya tienen dominio?',
                'tiene_hosting' => '¿Ya tienen hosting?',
                'necesita_correos_institucionales' => '¿Necesitan correos institucionales?',
            ];
            ?>
            <?php foreach ($cerradasPasoCuatro as $campo => $pregunta): ?>
              <fieldset class="im-campo im-campo--ancho" data-im-campo="radio">
                <legend><?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?></legend>
                <div class="im-muestra">
                  <label><input type="radio" name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" value="Sí" <?= requestPageExternalChecked($datos, $campo, 'Sí') ?> required> Sí</label>
                  <label><input type="radio" name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" value="No" <?= requestPageExternalChecked($datos, $campo, 'No') ?> required> No</label>
                </div>
                <small data-im-error>Seleccioná una opción.</small>
              </fieldset>
            <?php endforeach; ?>

            <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
              <span>Comentarios adicionales</span>
              <textarea name="comentarios_adicionales" rows="4" placeholder="¿Hay algo más que quieran compartir?" data-im-placeholder required><?= requestPageExternalValor($datos, 'comentarios_adicionales') ?></textarea>
              <small data-im-error>Completá este campo.</small>
              <em class="im-campo__contador" data-im-contador>0/5000</em>
            </label>

            <div class="im-formulario__acciones">
              <button class="im-boton im-boton--principal" type="submit">Enviar solicitud</button>
            </div>
          </section>
        </form>

        <div class="im-muestra">
          <button class="im-boton im-boton--texto" type="button" data-stepper-prev>Anterior</button>
          <button class="im-boton im-boton--principal" type="button" data-stepper-next>Siguiente</button>
        </div>
      </article>
    <?php endif; ?>
  </main>

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
      const titulos = ["Datos de contacto", "A quién está dirigida", "Estructura de tu página web", "Últimos detalles"];
      let indice = 0;

      const mostrarPaso = (nuevoIndice) => {
        indice = Math.max(0, Math.min(paneles.length - 1, nuevoIndice));
        pasos.forEach((paso, posicion) => paso.classList.toggle("activo", posicion === indice));
        paneles.forEach((panel, posicion) => panel.hidden = posicion !== indice);
        estado.textContent = `Paso ${indice + 1} de ${paneles.length}: ${titulos[indice]}`;
        anterior.hidden = indice === 0;
        siguiente.hidden = indice === paneles.length - 1;
      };

      const validarPaso = () => {
        const controles = [...paneles[indice].querySelectorAll("input, textarea, select")];
        const archivos = paneles[indice].querySelector('input[type="file"][name="imagenes_apoyo[]"]');
        if (archivos) {
          archivos.setCustomValidity(archivos.files.length > 10 ? "Podés adjuntar hasta 10 archivos." : "");
        }
        const invalido = controles.find((control) => !control.checkValidity());
        if (!invalido) {
          pasos[indice].classList.remove("error");
          return true;
        }
        pasos[indice].classList.add("error");
        invalido.reportValidity();
        invalido.focus();
        return false;
      };

      stepper.addEventListener("click", (evento) => {
        const botonPaso = evento.target.closest(".im-stepper__pasos button");
        const botonAnterior = evento.target.closest("[data-stepper-prev]");
        const botonSiguiente = evento.target.closest("[data-stepper-next]");
        if (!botonPaso && !botonAnterior && !botonSiguiente) return;

        evento.preventDefault();
        evento.stopPropagation();

        if (botonAnterior) {
          mostrarPaso(indice - 1);
          return;
        }
        if (botonSiguiente) {
          if (validarPaso()) mostrarPaso(indice + 1);
          return;
        }

        const destino = pasos.indexOf(botonPaso);
        if (destino <= indice || (destino === indice + 1 && validarPaso())) {
          mostrarPaso(destino);
        }
      }, true);

      formulario.addEventListener("submit", (evento) => {
        if (!validarPaso() || !formulario.checkValidity()) {
          evento.preventDefault();
          const primerInvalido = formulario.querySelector(":invalid");
          const panelInvalido = paneles.findIndex((panel) => panel.contains(primerInvalido));
          if (panelInvalido >= 0) mostrarPaso(panelInvalido);
          primerInvalido?.reportValidity();
        }
      });

      mostrarPaso(0);
    });
  </script>
</body>
</html>
