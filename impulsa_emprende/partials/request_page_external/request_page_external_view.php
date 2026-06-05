<?php
function valorFormulario(string $campo): string
{
    global $datos;
    return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}

$preguntas = [
    'q1_nombre_comercial' => ['Nombre comercial', '¿Cuál es el nombre comercial que debe mostrarse?'],
    'q2_actividad' => ['Actividad', 'Contanos qué hace el proyecto y qué ofrece.'],
    'q3_objetivo' => ['Objetivo', '¿Cuál es el objetivo principal de la página?'],
    'q4_publico' => ['Público', '¿A qué público querés llegar?'],
    'q5_accion_principal' => ['Acción principal', '¿Qué acción querés que realicen las visitas?'],
    'q6_propuestas_destacar' => ['Propuestas a destacar', '¿Qué productos, servicios o propuestas debemos destacar?'],
    'q7_diferencial' => ['Diferencial', '¿Qué diferencia al proyecto de otras alternativas?'],
    'q8_secciones' => ['Secciones', '¿Qué secciones debería incluir la página?'],
    'q9_textos' => ['Textos disponibles', '¿Ya contás con textos? Detallá cuáles.'],
    'q10_contacto' => ['Datos de contacto', 'Indicá los datos de contacto que deben publicarse.'],
    'q11_material_marca' => ['Material de marca', '¿Contás con logo, manual de marca o paleta de colores?'],
    'q12_estilo_visual' => ['Estilo visual', 'Describí el estilo visual que buscás.'],
    'q13_referencias' => ['Referencias', 'Compartí sitios o referencias que te gusten.'],
    'q14_recursos_visuales' => ['Recursos visuales', '¿Qué fotos, videos o ilustraciones deberían utilizarse?'],
    'q16_dominio_hosting' => ['Dominio y hosting', 'Indicá si ya contás con dominio y hosting.'],
    'q17_correos_corporativos' => ['Correos corporativos', '¿Necesitás correos corporativos? Detallá cuáles.'],
    'q18_requerimientos_adicionales' => ['Requerimientos adicionales', 'Agregá cualquier necesidad o aclaración adicional.'],
];
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

      <article class="im-tarjeta">
        <form class="im-formulario" action="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data" data-im-validar>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <input type="text" name="website" value="" tabindex="-1" autocomplete="off" hidden>

          <div class="im-formulario__separador">Datos de contacto</div>
          <label class="im-campo im-campo-material" data-im-campo="nombre">
            <span>Nombre y apellido</span>
            <input type="text" name="nombre" maxlength="150" value="<?= valorFormulario('nombre') ?>" required>
            <small data-im-error>Ingresá tu nombre.</small>
          </label>
          <label class="im-campo im-campo-material" data-im-campo="generico">
            <span>Nombre del proyecto</span>
            <input type="text" name="nombre_proyecto" maxlength="180" value="<?= valorFormulario('nombre_proyecto') ?>" required>
            <small data-im-error>Ingresá el nombre del proyecto.</small>
          </label>
          <label class="im-campo im-campo-material" data-im-campo="email">
            <span>Correo electrónico</span>
            <input type="email" name="correo" maxlength="190" value="<?= valorFormulario('correo') ?>" required>
            <small data-im-error>Ingresá un correo válido.</small>
          </label>
          <label class="im-campo im-campo-material" data-im-campo="whatsapp">
            <span>WhatsApp</span>
            <input type="tel" name="whatsapp" maxlength="80" value="<?= valorFormulario('whatsapp') ?>" required>
            <small data-im-error>Ingresá un WhatsApp válido.</small>
          </label>

          <div class="im-formulario__separador">Brief del proyecto</div>
          <?php foreach ($preguntas as $campo => [$titulo, $pregunta]): ?>
            <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="textarea" data-im-max="5000">
              <span><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></span>
              <textarea name="<?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?>" rows="4" maxlength="5000" placeholder="<?= htmlspecialchars($pregunta, ENT_QUOTES, 'UTF-8') ?>" data-im-placeholder required><?= valorFormulario($campo) ?></textarea>
              <small data-im-error>Completá este campo.</small>
              <em class="im-campo__contador" data-im-contador>0/5000</em>
            </label>
          <?php endforeach; ?>

          <div class="im-formulario__separador">Archivos de apoyo</div>
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Imágenes y documentos</span>
            <input type="file" name="archivos[]" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" multiple>
            <small>Hasta 10 archivos de 10 MB cada uno. Formatos: JPG, PNG, WEBP, PDF, DOC y DOCX.</small>
          </label>

          <div class="im-formulario__acciones">
            <button class="im-boton im-boton--principal" type="submit">Enviar solicitud</button>
          </div>
        </form>
      </article>
    <?php endif; ?>
  </main>
</body>
</html>
