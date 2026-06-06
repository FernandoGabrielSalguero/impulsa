<div class="im-modal-cortina" data-cerrar-alta-usuario></div>
<section class="im-dialog im-solicitud-modal" role="dialog" aria-modal="true" aria-labelledby="alta-usuario-titulo" aria-hidden="true" data-modal-alta-usuario>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea">Cliente externo</p>
      <h3 id="alta-usuario-titulo">Alta usuario</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-alta-usuario aria-label="Cerrar dialog"></button>
  </header>
  <form method="post" action="/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php">
    <div class="im-dialog__contenido">
      <input type="hidden" name="accion_solicitud_externa" value="alta_usuario">
      <input type="hidden" name="solicitud_externa_id" value="" data-alta-usuario-id>
      <div class="im-alerta im-alerta--info" data-alta-usuario-aviso>
        Se creara un usuario con rol impulsa_cliente y se enviaran las credenciales por correo.
      </div>
      <div class="im-solicitud-detalle">
        <div class="im-solicitud-detalle__item">
          <strong>Cliente</strong>
          <p data-alta-usuario-nombre>-</p>
        </div>
        <div class="im-solicitud-detalle__item">
          <strong>Correo de acceso</strong>
          <p data-alta-usuario-correo>-</p>
        </div>
        <div class="im-solicitud-detalle__item">
          <strong>Ingreso</strong>
          <p><a href="https://impulsagroup.com/ingreso.html" target="_blank" rel="noopener">https://impulsagroup.com/ingreso.html</a></p>
        </div>
      </div>
    </div>
    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-cerrar-alta-usuario>Cancelar</button>
      <a class="im-boton im-boton--tonal" href="https://impulsagroup.com/ingreso.html" target="_blank" rel="noopener">Ingresar</a>
      <button class="im-boton im-boton--principal" type="submit" data-alta-usuario-submit>Crear usuario</button>
    </footer>
  </form>
</section>

<script>
  (() => {
    const modal = document.querySelector('[data-modal-alta-usuario]');
    const cortina = document.querySelector('[data-cerrar-alta-usuario].im-modal-cortina');
    const inputId = document.querySelector('[data-alta-usuario-id]');
    const nombre = document.querySelector('[data-alta-usuario-nombre]');
    const correo = document.querySelector('[data-alta-usuario-correo]');
    const aviso = document.querySelector('[data-alta-usuario-aviso]');
    const submit = document.querySelector('[data-alta-usuario-submit]');

    if (!modal || !cortina || !inputId || !nombre || !correo || !aviso || !submit) {
      return;
    }

    const alternar = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    document.querySelectorAll('[data-alta-usuario-impulsa]').forEach((boton) => {
      boton.addEventListener('click', () => {
        const email = boton.dataset.solicitudCorreo || '';
        const clienteUserId = Number(boton.dataset.clienteUserId || 0);
        inputId.value = boton.dataset.altaUsuarioImpulsa || '';
        nombre.textContent = boton.dataset.solicitudNombre || 'Sin nombre';
        correo.textContent = email || 'Sin correo';
        submit.disabled = email.trim() === '' || clienteUserId > 0;
        aviso.textContent = clienteUserId > 0
          ? 'Ya existe un usuario asociado a este correo. No se creara un duplicado.'
          : (email.trim() === '' ? 'La solicitud no tiene correo. No se puede crear el usuario.' : 'Se creara un usuario con rol impulsa_cliente y se enviaran las credenciales por correo.');
        alternar(true);
      });
    });

    document.querySelectorAll('[data-cerrar-alta-usuario]').forEach((elemento) => {
      elemento.addEventListener('click', () => alternar(false));
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        alternar(false);
      }
    });
  })();
</script>
