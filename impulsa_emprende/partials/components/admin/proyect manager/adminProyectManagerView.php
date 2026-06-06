<div class="im-modal-cortina" data-cerrar-gestor-proyecto></div>
<section class="im-dialog im-proyecto-modal" role="dialog" aria-modal="true" aria-labelledby="gestor-proyecto-titulo" aria-hidden="true" data-modal-gestor-proyecto>
  <header class="im-dialog__cabecera">
    <div>
      <p class="im-sobrelinea" data-gestor-proyecto-subtitulo></p>
      <h3 id="gestor-proyecto-titulo">Gestor de proyectos</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-gestor-proyecto aria-label="Cerrar dialog"></button>
  </header>
  <div class="im-dialog__contenido">
    <div class="im-alerta im-alerta--info">Los objetivos se guardan como entregables del proyecto, agrupados por fase.</div>
    <div class="im-proyecto-lista" data-gestor-fases-lista></div>

    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Nueva fase</h3>
          <p>Define una etapa del proyecto con orden, estado y fecha objetivo.</p>
        </div>
      </div>
      <form class="im-formulario" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
        <input type="hidden" name="accion_proyecto" value="pm_crear_fase">
        <input type="hidden" name="project_id" data-pm-fase-project-id>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Titulo</span><input type="text" name="title" maxlength="180" required></label>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="3"></textarea></label>
        <label class="im-campo im-campo-material"><span>Orden</span><input type="number" name="phase_order" min="1" value="1" required></label>
        <label class="im-campo im-campo-material"><span>Duracion dias</span><input type="number" name="duration_days" min="1"></label>
        <label class="im-campo im-campo-material"><span>Fecha estimada</span><input type="date" name="due_date"></label>
        <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending">Pendiente</option><option value="in_progress">En progreso</option><option value="blocked">Bloqueada</option><option value="done">Finalizada</option></select></label>
        <div class="im-formulario__acciones"><button class="im-boton im-boton--principal" type="submit">Crear fase</button></div>
      </form>
    </article>
  </div>
  <footer class="im-dialog__acciones">
    <button class="im-boton im-boton--texto" type="button" data-cerrar-gestor-proyecto>Cerrar</button>
  </footer>
</section>

<script>
  (() => {
    const proyectos = <?= json_encode($proyectos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const fases = <?= json_encode($fasesPorProyecto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const objetivos = <?= json_encode($objetivosPorProyecto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const modal = document.querySelector('[data-modal-gestor-proyecto]');
    const cortina = document.querySelector('[data-cerrar-gestor-proyecto].im-modal-cortina');
    const lista = document.querySelector('[data-gestor-fases-lista]');
    const subtitulo = document.querySelector('[data-gestor-proyecto-subtitulo]');
    const inputFaseProject = document.querySelector('[data-pm-fase-project-id]');

    if (!modal || !cortina || !lista || !subtitulo || !inputFaseProject) {
      return;
    }

    const proyectosPorId = new Map(proyectos.map((proyecto) => [String(proyecto.id), proyecto]));
    const escapeHtml = (value) => String(value ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    const estado = (value) => String(value || '').replaceAll('_', ' ');
    const proyectoNombre = (id) => proyectosPorId.get(String(id))?.project_name || `Proyecto #${id}`;
    const selected = (actual, esperado) => String(actual || '') === String(esperado) ? ' selected' : '';
    const checked = (valor) => Number(valor || 0) === 1 ? ' checked' : '';

    const alternar = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
    };

    const renderObjetivo = (projectId, fase, objetivo) => `
      <form class="im-proyecto-lista__item" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
        <input type="hidden" name="accion_proyecto" value="pm_editar_objetivo">
        <input type="hidden" name="project_id" value="${Number(projectId)}">
        <input type="hidden" name="objective_id" value="${Number(objetivo.id)}">
        <label class="im-campo im-campo-material im-campo--ancho"><span>Objetivo</span><input type="text" name="title" maxlength="180" value="${escapeHtml(objetivo.title)}" required></label>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="2">${escapeHtml(objetivo.description || '')}</textarea></label>
        <label class="im-campo im-campo-material"><span>Fase</span><select name="phase_id">${(fases[projectId] || []).map((item) => `<option value="${Number(item.id)}"${selected(objetivo.phase_id, item.id)}>${escapeHtml(item.title)}</option>`).join('')}</select></label>
        <label class="im-campo im-campo-material"><span>Tipo</span><select name="deliverable_type"><option value="document"${selected(objetivo.deliverable_type, 'document')}>Documento</option><option value="design"${selected(objetivo.deliverable_type, 'design')}>Diseno</option><option value="development"${selected(objetivo.deliverable_type, 'development')}>Desarrollo</option><option value="deployment"${selected(objetivo.deliverable_type, 'deployment')}>Publicacion</option><option value="training"${selected(objetivo.deliverable_type, 'training')}>Capacitacion</option><option value="other"${selected(objetivo.deliverable_type, 'other')}>Otro</option></select></label>
        <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending"${selected(objetivo.status, 'pending')}>Pendiente</option><option value="in_progress"${selected(objetivo.status, 'in_progress')}>En progreso</option><option value="ready_for_review"${selected(objetivo.status, 'ready_for_review')}>Listo para revision</option><option value="delivered"${selected(objetivo.status, 'delivered')}>Entregado</option></select></label>
        <label class="im-campo im-campo-material"><span>Fecha limite</span><input type="date" name="due_date" value="${escapeHtml(objetivo.due_date || '')}"></label>
        <label class="im-slide-toggle"><input type="checkbox" name="client_visible"${checked(objetivo.client_visible)}><span></span>Visible para cliente</label>
        <div class="im-formulario__acciones"><button class="im-boton im-boton--tonal" type="submit">Guardar objetivo</button></div>
      </form>
    `;

    const renderFase = (projectId, fase) => {
      const objetivosFase = (objetivos[projectId] || []).filter((objetivo) => Number(objetivo.phase_id) === Number(fase.id));
      return `
        <article class="im-tarjeta">
          <form class="im-formulario" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
            <input type="hidden" name="accion_proyecto" value="pm_editar_fase">
            <input type="hidden" name="project_id" value="${Number(projectId)}">
            <input type="hidden" name="phase_id" value="${Number(fase.id)}">
            <label class="im-campo im-campo-material im-campo--ancho"><span>Fase</span><input type="text" name="title" maxlength="180" value="${escapeHtml(fase.title)}" required></label>
            <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="2">${escapeHtml(fase.description || '')}</textarea></label>
            <label class="im-campo im-campo-material"><span>Orden</span><input type="number" name="phase_order" min="1" value="${Number(fase.phase_order || 1)}" required></label>
            <label class="im-campo im-campo-material"><span>Duracion dias</span><input type="number" name="duration_days" min="1" value="${escapeHtml(fase.duration_days || '')}"></label>
            <label class="im-campo im-campo-material"><span>Fecha estimada</span><input type="date" name="due_date" value="${escapeHtml(fase.due_date || '')}"></label>
            <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending"${selected(fase.status, 'pending')}>Pendiente</option><option value="in_progress"${selected(fase.status, 'in_progress')}>En progreso</option><option value="blocked"${selected(fase.status, 'blocked')}>Bloqueada</option><option value="done"${selected(fase.status, 'done')}>Finalizada</option></select></label>
            <div class="im-formulario__acciones"><span class="im-chip">${escapeHtml(estado(fase.status))}</span><button class="im-boton im-boton--tonal" type="submit">Guardar fase</button></div>
          </form>
          <div class="im-proyecto-lista">
            <h4>Objetivos de esta fase</h4>
            ${objetivosFase.map((objetivo) => renderObjetivo(projectId, fase, objetivo)).join('') || '<div class="im-alerta im-alerta--info">Esta fase no tiene objetivos.</div>'}
            <form class="im-proyecto-lista__item" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
              <input type="hidden" name="accion_proyecto" value="pm_crear_objetivo">
              <input type="hidden" name="project_id" value="${Number(projectId)}">
              <input type="hidden" name="phase_id" value="${Number(fase.id)}">
              <label class="im-campo im-campo-material im-campo--ancho"><span>Nuevo objetivo</span><input type="text" name="title" maxlength="180" required></label>
              <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="2"></textarea></label>
              <label class="im-campo im-campo-material"><span>Tipo</span><select name="deliverable_type"><option value="document">Documento</option><option value="design">Diseno</option><option value="development">Desarrollo</option><option value="deployment">Publicacion</option><option value="training">Capacitacion</option><option value="other">Otro</option></select></label>
              <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending">Pendiente</option><option value="in_progress">En progreso</option><option value="ready_for_review">Listo para revision</option><option value="delivered">Entregado</option></select></label>
              <label class="im-campo im-campo-material"><span>Fecha limite</span><input type="date" name="due_date"></label>
              <label class="im-slide-toggle"><input type="checkbox" name="client_visible" checked><span></span>Visible para cliente</label>
              <div class="im-formulario__acciones"><button class="im-boton im-boton--principal" type="submit">Crear objetivo</button></div>
            </form>
          </div>
        </article>
      `;
    };

    document.querySelectorAll('[data-abrir-gestor-proyecto]').forEach((boton) => {
      boton.addEventListener('click', () => {
        const projectId = boton.dataset.abrirGestorProyecto;
        inputFaseProject.value = projectId;
        subtitulo.textContent = proyectoNombre(projectId);
        lista.innerHTML = (fases[projectId] || []).map((fase) => renderFase(projectId, fase)).join('') || '<div class="im-alerta im-alerta--info">Todavia no hay fases cargadas. Crea la primera fase desde el formulario inferior.</div>';
        alternar(true);
      });
    });

    document.querySelectorAll('[data-cerrar-gestor-proyecto]').forEach((elemento) => elemento.addEventListener('click', () => alternar(false)));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') alternar(false); });
  })();
</script>
