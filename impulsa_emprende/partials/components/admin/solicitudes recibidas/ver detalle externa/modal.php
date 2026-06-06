<div class="im-modal-cortina" data-cerrar-solicitud></div>
<section class="im-dialog im-solicitud-modal" role="dialog" aria-modal="true" aria-labelledby="solicitud-modal-titulo" aria-hidden="true" data-modal-solicitud>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea" data-modal-fecha></p>
      <h3 id="solicitud-modal-titulo" data-modal-titulo>Solicitud</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-solicitud aria-label="Cerrar dialog"></button>
  </header>
  <div class="im-dialog__contenido">
    <div class="im-chip-lista">
      <span class="im-chip" data-modal-contacto></span>
      <span class="im-chip" data-modal-correo></span>
      <span class="im-chip" data-modal-telefono></span>
    </div>
    <div class="im-solicitud-detalle" data-modal-detalle></div>
  </div>
  <footer class="im-dialog__acciones">
    <button class="im-boton im-boton--texto" type="button" data-cerrar-solicitud>Cerrar</button>
  </footer>
</section>

<div class="im-modal-cortina" data-cerrar-solicitud-impulsa></div>
<section class="im-dialog im-solicitud-modal" role="dialog" aria-modal="true" aria-labelledby="solicitud-impulsa-modal-titulo" aria-hidden="true" data-modal-solicitud-impulsa>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea" data-modal-impulsa-fecha></p>
      <h3 id="solicitud-impulsa-modal-titulo" data-modal-impulsa-titulo>Solicitud</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-solicitud-impulsa aria-label="Cerrar dialog"></button>
  </header>
  <div class="im-dialog__contenido">
    <div class="im-chip-lista">
      <span class="im-chip" data-modal-impulsa-contacto></span>
      <span class="im-chip" data-modal-impulsa-correo></span>
      <span class="im-chip" data-modal-impulsa-telefono></span>
    </div>
    <div class="im-solicitud-detalle" data-modal-impulsa-detalle></div>
  </div>
  <footer class="im-dialog__acciones">
    <button class="im-boton im-boton--texto" type="button" data-cerrar-solicitud-impulsa>Cerrar</button>
  </footer>
</section>

<script>
  (() => {
    const solicitudes = <?= json_encode($solicitudesPaginaWeb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const solicitudesImpulsa = <?= json_encode($solicitudesPaginaWebExternas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const preguntas = <?= json_encode($preguntasSolicitud, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const preguntasImpulsa = <?= json_encode($preguntasSolicitudExterna, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const camposBooleanos = new Set(<?= json_encode($camposBooleanosSolicitud, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    const modal = document.querySelector('[data-modal-solicitud]');
    const cortina = document.querySelector('[data-cerrar-solicitud].im-modal-cortina');
    const detalle = document.querySelector('[data-modal-detalle]');
    const titulo = document.querySelector('[data-modal-titulo]');
    const fecha = document.querySelector('[data-modal-fecha]');
    const contacto = document.querySelector('[data-modal-contacto]');
    const correo = document.querySelector('[data-modal-correo]');
    const telefono = document.querySelector('[data-modal-telefono]');
    const modalImpulsa = document.querySelector('[data-modal-solicitud-impulsa]');
    const cortinaImpulsa = document.querySelector('[data-cerrar-solicitud-impulsa].im-modal-cortina');
    const detalleImpulsa = document.querySelector('[data-modal-impulsa-detalle]');
    const tituloImpulsa = document.querySelector('[data-modal-impulsa-titulo]');
    const fechaImpulsa = document.querySelector('[data-modal-impulsa-fecha]');
    const contactoImpulsa = document.querySelector('[data-modal-impulsa-contacto]');
    const correoImpulsa = document.querySelector('[data-modal-impulsa-correo]');
    const telefonoImpulsa = document.querySelector('[data-modal-impulsa-telefono]');

    if (!modal || !cortina || !detalle || !titulo || !fecha || !contacto || !correo || !telefono || !modalImpulsa || !cortinaImpulsa || !detalleImpulsa || !tituloImpulsa || !fechaImpulsa || !contactoImpulsa || !correoImpulsa || !telefonoImpulsa) {
      return;
    }

    const solicitudesPorId = new Map(solicitudes.map((solicitud) => [String(solicitud.id), solicitud]));
    const solicitudesImpulsaPorId = new Map(solicitudesImpulsa.map((solicitud) => [String(solicitud.id), solicitud]));
    const escapeHtml = (value) => String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const formatearFecha = (valor) => {
      if (!valor) {
        return '';
      }

      const date = new Date(String(valor).replace(' ', 'T'));
      if (Number.isNaN(date.getTime())) {
        return String(valor);
      }

      return new Intl.DateTimeFormat('es-AR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      }).format(date);
    };

    const alternarModal = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    const alternarModalImpulsa = (abrir) => {
      modalImpulsa.classList.toggle('abierto', abrir);
      cortinaImpulsa.classList.toggle('abierto', abrir);
      modalImpulsa.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    const nombreSolicitante = (solicitud) => {
      const nombreCompleto = `${solicitud.usuario_nombre ?? ''} ${solicitud.usuario_apellido ?? ''}`.trim();
      return nombreCompleto || solicitud.usuario_apodo || 'Sin nombre';
    };

    const valorDetalle = (solicitud, campo) => {
      if (camposBooleanos.has(campo)) {
        return Number(solicitud[campo] ?? 0) === 1 ? 'Si' : 'No';
      }

      if (campo === 'fecha_inicio') {
        return formatearFecha(solicitud[campo]);
      }

      return solicitud[campo] || '-';
    };

    const valorDetalleImpulsa = (solicitud, campo) => {
      if (campo === 'created_at') {
        return formatearFecha(solicitud[campo]);
      }

      return solicitud[campo] || '-';
    };

    const abrirSolicitud = (solicitud) => {
      titulo.textContent = solicitud.nombre_emprendimiento || 'Solicitud de landing page';
      fecha.textContent = formatearFecha(solicitud.created_at);
      contacto.textContent = nombreSolicitante(solicitud);
      correo.textContent = solicitud.usuario_correo || 'Sin correo';
      telefono.textContent = solicitud.telefono_contacto || 'Sin telefono';
      detalle.innerHTML = Object.entries(preguntas).map(([campo, label]) => `
        <div class="im-solicitud-detalle__item">
          <strong>${escapeHtml(label)}</strong>
          <p>${escapeHtml(valorDetalle(solicitud, campo))}</p>
        </div>
      `).join('');
      alternarModal(true);
    };

    const abrirSolicitudImpulsa = (solicitud) => {
      tituloImpulsa.textContent = solicitud.nombre_proyecto || 'Solicitud de landing page';
      fechaImpulsa.textContent = formatearFecha(solicitud.created_at);
      contactoImpulsa.textContent = solicitud.nombre || 'Sin nombre';
      correoImpulsa.textContent = solicitud.correo || 'Sin correo';
      telefonoImpulsa.textContent = solicitud.whatsapp || 'Sin WhatsApp';
      detalleImpulsa.innerHTML = Object.entries(preguntasImpulsa).map(([campo, label]) => `
        <div class="im-solicitud-detalle__item">
          <strong>${escapeHtml(label)}</strong>
          <p>${escapeHtml(valorDetalleImpulsa(solicitud, campo))}</p>
        </div>
      `).join('');
      alternarModalImpulsa(true);
    };

    document.querySelectorAll('[data-ver-solicitud]').forEach((boton) => {
      boton.addEventListener('click', () => {
        const solicitud = solicitudesPorId.get(String(boton.dataset.verSolicitud));
        if (solicitud) {
          abrirSolicitud(solicitud);
        }
      });
    });

    document.querySelectorAll('[data-ver-solicitud-impulsa]').forEach((boton) => {
      boton.addEventListener('click', () => {
        const solicitud = solicitudesImpulsaPorId.get(String(boton.dataset.verSolicitudImpulsa));
        if (solicitud) {
          abrirSolicitudImpulsa(solicitud);
        }
      });
    });

    document.querySelectorAll('[data-cerrar-solicitud]').forEach((elemento) => {
      elemento.addEventListener('click', () => alternarModal(false));
    });

    document.querySelectorAll('[data-cerrar-solicitud-impulsa]').forEach((elemento) => {
      elemento.addEventListener('click', () => alternarModalImpulsa(false));
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        alternarModal(false);
        alternarModalImpulsa(false);
      }
    });
  })();
</script>
