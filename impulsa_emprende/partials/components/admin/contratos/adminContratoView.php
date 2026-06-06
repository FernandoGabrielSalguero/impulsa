<div class="im-modal-cortina" data-cerrar-contrato-admin></div>
<section class="im-dialog im-proyecto-modal" role="dialog" aria-modal="true" aria-labelledby="modal-contrato-titulo" aria-hidden="true" data-modal-contrato-admin>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea" data-contrato-proyecto></p>
      <h3 id="modal-contrato-titulo">Contrato</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-contrato-admin aria-label="Cerrar dialog"></button>
  </header>
  <form method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
    <input type="hidden" name="accion_proyecto" value="contrato_guardar">
    <input type="hidden" name="project_id" data-contrato-project-id>
    <div class="im-dialog__contenido">
      <div class="im-alerta im-alerta--info" data-contrato-estado></div>
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Nombre del contrato</span>
        <input type="text" name="contract_name" maxlength="180" required data-contrato-nombre>
      </label>
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Contenido</span>
        <textarea name="contract_text" rows="12" required data-contrato-texto></textarea>
      </label>
    </div>
    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-cerrar-contrato-admin>Cancelar</button>
      <button class="im-boton im-boton--principal" type="submit">Guardar contrato</button>
    </footer>
  </form>
</section>

<script>
  (() => {
    const proyectos = <?= json_encode($proyectos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const contratos = <?= json_encode($contratosPorProyecto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const modal = document.querySelector('[data-modal-contrato-admin]');
    const cortina = document.querySelector('[data-cerrar-contrato-admin].im-modal-cortina');

    if (!modal || !cortina) {
      return;
    }

    const proyectosPorId = new Map(proyectos.map((proyecto) => [String(proyecto.id), proyecto]));
    const proyectoNombre = (id) => proyectosPorId.get(String(id))?.project_name || `Proyecto #${id}`;
    const alternar = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    document.querySelectorAll('[data-abrir-contrato-proyecto]').forEach((boton) => {
      boton.addEventListener('click', () => {
        const projectId = boton.dataset.abrirContratoProyecto;
        const contrato = contratos[projectId] || null;
        modal.querySelector('[data-contrato-project-id]').value = projectId;
        modal.querySelector('[data-contrato-proyecto]').textContent = proyectoNombre(projectId);
        modal.querySelector('[data-contrato-estado]').textContent = contrato
          ? `Contrato existente. Se guardara como version ${Number(contrato.version_number || 1) + 1}.`
          : 'No hay contrato cargado. Se creara el contrato del proyecto.';
        modal.querySelector('[data-contrato-nombre]').value = contrato?.contract_name || `Contrato - ${proyectoNombre(projectId)}`;
        modal.querySelector('[data-contrato-texto]').value = contrato?.contract_text || '';
        alternar(true);
      });
    });

    document.querySelectorAll('[data-cerrar-contrato-admin]').forEach((elemento) => elemento.addEventListener('click', () => alternar(false)));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') alternar(false); });
  })();
</script>
