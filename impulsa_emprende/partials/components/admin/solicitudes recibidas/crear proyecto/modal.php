<div class="im-modal-cortina" data-cerrar-crear-proyecto></div>
<section class="im-dialog im-solicitud-modal" role="dialog" aria-modal="true" aria-labelledby="crear-proyecto-titulo" aria-hidden="true" data-modal-crear-proyecto>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea">Proyecto cliente</p>
      <h3 id="crear-proyecto-titulo">Crear proyecto</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-crear-proyecto aria-label="Cerrar dialog"></button>
  </header>
  <form method="post" action="/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php">
    <div class="im-dialog__contenido">
      <input type="hidden" name="accion_solicitud" value="crear_proyecto">
      <input type="hidden" name="solicitud_tipo" value="externa" data-crear-proyecto-tipo>
      <input type="hidden" name="solicitud_id" value="" data-crear-proyecto-id>
      <div class="im-alerta im-alerta--info" data-crear-proyecto-aviso>
        Se creara un proyecto visible para el usuario cliente.
      </div>
      <div class="im-solicitud-detalle">
        <div class="im-solicitud-detalle__item">
          <strong>Proyecto</strong>
          <p data-crear-proyecto-nombre>-</p>
        </div>
        <div class="im-solicitud-detalle__item">
          <strong data-crear-proyecto-label-cliente>Cliente</strong>
          <p data-crear-proyecto-solicitante>-</p>
        </div>
        <div class="im-solicitud-detalle__item">
          <strong>Correo</strong>
          <p data-crear-proyecto-correo>-</p>
        </div>
        <div class="im-solicitud-detalle__item">
          <strong>Estructura inicial</strong>
          <p>Se prepararan fases, entregables y una actualizacion inicial visible para el cliente.</p>
        </div>
      </div>
    </div>
    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-cerrar-crear-proyecto>Cancelar</button>
      <button class="im-boton im-boton--principal" type="submit" data-crear-proyecto-submit>Crear proyecto</button>
    </footer>
  </form>
</section>

<script>
  (() => {
    const modal = document.querySelector('[data-modal-crear-proyecto]');
    const cortina = document.querySelector('[data-cerrar-crear-proyecto].im-modal-cortina');
    const inputId = document.querySelector('[data-crear-proyecto-id]');
    const inputTipo = document.querySelector('[data-crear-proyecto-tipo]');
    const nombre = document.querySelector('[data-crear-proyecto-nombre]');
    const nombreSolicitante = document.querySelector('[data-crear-proyecto-solicitante]');
    const correo = document.querySelector('[data-crear-proyecto-correo]');
    const labelCliente = document.querySelector('[data-crear-proyecto-label-cliente]');
    const aviso = document.querySelector('[data-crear-proyecto-aviso]');
    const submit = document.querySelector('[data-crear-proyecto-submit]');

    if (!modal || !cortina || !inputId || !inputTipo || !nombre || !nombreSolicitante || !correo || !labelCliente || !aviso || !submit) {
      return;
    }

    const alternar = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    document.querySelectorAll('[data-crear-proyecto]').forEach((boton) => {
      boton.addEventListener('click', () => {
        const solicitudTipo = boton.dataset.solicitudTipo || 'externa';
        const clienteUserId = Number(boton.dataset.clienteUserId || 0);
        const proyectoId = Number(boton.dataset.proyectoId || 0);
        inputId.value = boton.dataset.crearProyecto || '';
        inputTipo.value = solicitudTipo;
        nombre.textContent = boton.dataset.solicitudProyecto || 'Sin nombre de proyecto';
        nombreSolicitante.textContent = boton.dataset.solicitudNombre || 'Sin nombre';
        correo.textContent = boton.dataset.solicitudCorreo || 'Sin correo';
        labelCliente.textContent = solicitudTipo === 'interna' ? 'Solicitante' : 'Cliente';

        if (proyectoId > 0) {
          submit.disabled = true;
          aviso.textContent = 'Ya existe un proyecto creado para esta solicitud. No se creara un duplicado.';
        } else if (solicitudTipo !== 'interna' && clienteUserId <= 0) {
          submit.disabled = true;
          aviso.textContent = 'Primero tenes que generar el usuario cliente desde la opcion Alta usuario.';
        } else {
          submit.disabled = false;
          aviso.textContent = solicitudTipo === 'interna'
            ? 'Se creara un proyecto visible para el usuario registrado con fases y entregables iniciales.'
            : 'Se creara un proyecto visible para el usuario cliente con fases y entregables iniciales.';
        }

        alternar(true);
      });
    });

    document.querySelectorAll('[data-cerrar-crear-proyecto]').forEach((elemento) => {
      elemento.addEventListener('click', () => alternar(false));
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        alternar(false);
      }
    });
  })();
</script>
