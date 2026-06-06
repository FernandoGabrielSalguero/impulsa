<?php
$pmResponsables = $pmResponsables ?? [];
$pmH = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
?>
<div class="im-modal-cortina" data-cerrar-gestor-proyecto></div>
<section class="im-dialog im-proyecto-modal" role="dialog" aria-modal="true" aria-labelledby="gestor-proyecto-titulo" aria-hidden="true" data-modal-gestor-proyecto>
  <header class="im-dialog__cabecera im-pm-cabecera">
    <div>
      <p class="im-sobrelinea" data-gestor-proyecto-subtitulo>Proyecto</p>
      <h3 id="gestor-proyecto-titulo">Gestor de proyectos</h3>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-gestor-proyecto aria-label="Cerrar dialog"></button>
  </header>

  <div class="im-dialog__contenido im-pm-contenido">
    <section class="im-pm-panel im-pm-panel--proyecto" aria-labelledby="pm-datos-proyecto-titulo">
      <div class="im-pm-panel__cabecera">
        <div>
          <p class="im-sobrelinea">Datos del proyecto</p>
          <h4 id="pm-datos-proyecto-titulo" data-pm-proyecto-nombre>Proyecto</h4>
          <p data-pm-proyecto-contexto></p>
        </div>
        <div class="im-pm-progreso" aria-live="polite">
          <strong data-pm-progreso-calculado>0%</strong>
          <span data-pm-progreso-detalle>Sin objetivos todavia</span>
        </div>
      </div>

      <form class="im-pm-form im-pm-form--proyecto" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php" data-pm-proyecto-form>
        <input type="hidden" name="accion_proyecto" value="pm_actualizar_proyecto">
        <input type="hidden" name="project_id">

        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Nombre del proyecto</span>
          <input type="text" name="project_name" maxlength="180" required>
        </label>
        <label class="im-campo im-campo-material">
          <span>Responsable</span>
          <select name="manager_user_id" required>
            <?php foreach ($pmResponsables as $responsable): ?>
              <?php
                $nombreResponsable = trim((string) (($responsable['nombre'] ?? '') . ' ' . ($responsable['apellido'] ?? '')));
                $labelResponsable = $nombreResponsable !== '' ? $nombreResponsable . ' - ' . ($responsable['correo'] ?? '') : ($responsable['correo'] ?? '');
              ?>
              <option value="<?= (int) ($responsable['id'] ?? 0) ?>"><?= $pmH($labelResponsable) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Estado general</span>
          <select name="status">
            <option value="draft">Borrador</option>
            <option value="planned">Planificado</option>
            <option value="in_progress">En progreso</option>
            <option value="paused">Pausado</option>
            <option value="in_review">En revision</option>
            <option value="completed">Completado</option>
            <option value="cancelled">Cancelado</option>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Prioridad</span>
          <select name="priority">
            <option value="low">Baja</option>
            <option value="medium">Media</option>
            <option value="high">Alta</option>
            <option value="urgent">Urgente</option>
          </select>
        </label>
        <label class="im-campo im-campo-material">
          <span>Fecha de inicio</span>
          <input type="date" name="start_date">
        </label>
        <label class="im-campo im-campo-material">
          <span>Finalizacion estimada</span>
          <input type="date" name="target_delivery_date">
        </label>
        <label class="im-campo im-campo-material">
          <span>Avance manual</span>
          <input type="number" name="progress_percent" min="0" max="100">
        </label>
        <label class="im-slide-toggle im-pm-toggle">
          <input type="checkbox" name="client_visible">
          <span></span>Visible para cliente
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Descripcion</span>
          <textarea name="summary" rows="2"></textarea>
        </label>
        <label class="im-campo im-campo-material im-campo--ancho">
          <span>Alcance</span>
          <textarea name="scope_summary" rows="2"></textarea>
        </label>

        <div class="im-formulario__acciones im-pm-form__acciones">
          <button class="im-boton im-boton--principal" type="submit">Guardar datos del proyecto</button>
        </div>
      </form>
    </section>

    <section class="im-pm-panel" aria-labelledby="pm-fases-titulo">
      <div class="im-pm-panel__cabecera">
        <div>
          <p class="im-sobrelinea">Fases y objetivos</p>
          <h4 id="pm-fases-titulo">Tablero del proyecto</h4>
        </div>
        <button class="im-boton im-boton--tonal" type="button" data-pm-toggle="[data-pm-nueva-fase]">
          <span class="material-symbols-rounded" aria-hidden="true">add</span>
          Nueva fase
        </button>
      </div>

      <form class="im-pm-form im-pm-form--compacto" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php" data-pm-nueva-fase hidden>
        <input type="hidden" name="accion_proyecto" value="pm_crear_fase">
        <input type="hidden" name="project_id" data-pm-fase-project-id>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Nombre de la fase</span><input type="text" name="title" maxlength="180" required></label>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion corta</span><textarea name="description" rows="2"></textarea></label>
        <label class="im-campo im-campo-material"><span>Orden</span><input type="number" name="phase_order" min="1" value="1" required></label>
        <label class="im-campo im-campo-material"><span>Duracion dias</span><input type="number" name="duration_days" min="1"></label>
        <label class="im-campo im-campo-material"><span>Fecha estimada</span><input type="date" name="due_date"></label>
        <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending">Pendiente</option><option value="in_progress">En progreso</option><option value="blocked">Bloqueada</option><option value="done">Finalizada</option></select></label>
        <div class="im-formulario__acciones im-pm-form__acciones">
          <button class="im-boton im-boton--principal" type="submit">Guardar fase</button>
          <button class="im-boton im-boton--texto" type="button" data-pm-toggle="[data-pm-nueva-fase]">Cancelar</button>
        </div>
      </form>

      <div class="im-pm-tablero" data-gestor-fases-lista></div>
    </section>
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
    const tablero = document.querySelector('[data-gestor-fases-lista]');
    const subtitulo = document.querySelector('[data-gestor-proyecto-subtitulo]');
    const nombreProyecto = document.querySelector('[data-pm-proyecto-nombre]');
    const contextoProyecto = document.querySelector('[data-pm-proyecto-contexto]');
    const progresoCalculado = document.querySelector('[data-pm-progreso-calculado]');
    const progresoDetalle = document.querySelector('[data-pm-progreso-detalle]');
    const formProyecto = document.querySelector('[data-pm-proyecto-form]');
    const inputFaseProject = document.querySelector('[data-pm-fase-project-id]');
    const formNuevaFase = document.querySelector('[data-pm-nueva-fase]');

    if (!modal || !cortina || !tablero || !subtitulo || !formProyecto || !inputFaseProject) {
      return;
    }

    const proyectosPorId = new Map(proyectos.map((proyecto) => [String(proyecto.id), proyecto]));
    const escapeHtml = (value) => String(value ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
    const selected = (actual, esperado) => String(actual || '') === String(esperado) ? ' selected' : '';
    const checked = (valor) => Number(valor || 0) === 1 ? ' checked' : '';
    const fecha = (value) => value ? escapeHtml(value) : 'Sin fecha';
    const texto = (value, fallback = 'Sin cargar') => String(value || '').trim() || fallback;
    const estadoTexto = {
      draft: 'Borrador',
      planned: 'Planificado',
      in_progress: 'En progreso',
      paused: 'Pausado',
      in_review: 'En revision',
      completed: 'Completado',
      cancelled: 'Cancelado',
      pending: 'Pendiente',
      blocked: 'Bloqueada',
      done: 'Finalizada',
      ready_for_review: 'Listo para revision',
      delivered: 'Entregado'
    };
    const tipoTexto = {
      document: 'Documento',
      design: 'Diseno',
      development: 'Desarrollo',
      deployment: 'Publicacion',
      training: 'Capacitacion',
      other: 'Otro'
    };
    const estadoLabel = (value) => estadoTexto[value] || String(value || '').replaceAll('_', ' ');
    const tipoLabel = (value) => tipoTexto[value] || 'Otro';
    const safeId = (prefix, id) => `${prefix}-${Number(id)}`;

    const alternar = (abrir) => {
      modal.classList.toggle('abierto', abrir);
      cortina.classList.toggle('abierto', abrir);
      modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
      if (!abrir && formNuevaFase) {
        formNuevaFase.hidden = true;
      }
    };

    const setFormValue = (form, name, value) => {
      if (form.elements[name]) {
        form.elements[name].value = value ?? '';
      }
    };

    const setProjectForm = (proyecto) => {
      setFormValue(formProyecto, 'project_id', proyecto.id);
      setFormValue(formProyecto, 'project_name', proyecto.project_name);
      setFormValue(formProyecto, 'manager_user_id', proyecto.manager_user_id);
      setFormValue(formProyecto, 'status', proyecto.status || 'planned');
      setFormValue(formProyecto, 'priority', proyecto.priority || 'medium');
      setFormValue(formProyecto, 'start_date', proyecto.start_date);
      setFormValue(formProyecto, 'target_delivery_date', proyecto.target_delivery_date);
      setFormValue(formProyecto, 'progress_percent', proyecto.progress_percent ?? 0);
      setFormValue(formProyecto, 'summary', proyecto.summary);
      setFormValue(formProyecto, 'scope_summary', proyecto.scope_summary);
      formProyecto.elements.client_visible.checked = Number(proyecto.client_visible || 0) === 1;
    };

    const renderProgreso = (projectId, proyecto) => {
      const listaObjetivos = objetivos[projectId] || [];
      const total = listaObjetivos.length;
      const completados = listaObjetivos.filter((objetivo) => objetivo.status === 'delivered').length;
      const porcentaje = total > 0 ? Math.round((completados / total) * 100) : 0;
      progresoCalculado.textContent = `${porcentaje}%`;
      progresoDetalle.textContent = total > 0
        ? `${completados} de ${total} objetivos completados - avance manual ${Number(proyecto.progress_percent || 0)}%`
        : 'Este proyecto todavia no tiene objetivos cargados.';
    };

    const renderObjetivoForm = (projectId, objetivo, fasesProyecto) => `
      <form class="im-pm-form im-pm-form--compacto im-pm-form--objetivo" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php" id="${safeId('pm-objetivo-form', objetivo.id)}" hidden>
        <input type="hidden" name="accion_proyecto" value="pm_editar_objetivo">
        <input type="hidden" name="project_id" value="${Number(projectId)}">
        <input type="hidden" name="objective_id" value="${Number(objetivo.id)}">
        <label class="im-campo im-campo-material im-campo--ancho"><span>Objetivo</span><input type="text" name="title" maxlength="180" value="${escapeHtml(objetivo.title)}" required></label>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="2">${escapeHtml(objetivo.description || '')}</textarea></label>
        <label class="im-campo im-campo-material"><span>Fase</span><select name="phase_id">${fasesProyecto.map((item) => `<option value="${Number(item.id)}"${selected(objetivo.phase_id, item.id)}>${escapeHtml(item.title)}</option>`).join('')}</select></label>
        <label class="im-campo im-campo-material"><span>Tipo</span><select name="deliverable_type"><option value="document"${selected(objetivo.deliverable_type, 'document')}>Documento</option><option value="design"${selected(objetivo.deliverable_type, 'design')}>Diseno</option><option value="development"${selected(objetivo.deliverable_type, 'development')}>Desarrollo</option><option value="deployment"${selected(objetivo.deliverable_type, 'deployment')}>Publicacion</option><option value="training"${selected(objetivo.deliverable_type, 'training')}>Capacitacion</option><option value="other"${selected(objetivo.deliverable_type, 'other')}>Otro</option></select></label>
        <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending"${selected(objetivo.status, 'pending')}>Pendiente</option><option value="in_progress"${selected(objetivo.status, 'in_progress')}>En progreso</option><option value="ready_for_review"${selected(objetivo.status, 'ready_for_review')}>Listo para revision</option><option value="delivered"${selected(objetivo.status, 'delivered')}>Entregado</option></select></label>
        <label class="im-campo im-campo-material"><span>Fecha limite</span><input type="date" name="due_date" value="${escapeHtml(objetivo.due_date || '')}"></label>
        <label class="im-slide-toggle im-pm-toggle"><input type="checkbox" name="client_visible"${checked(objetivo.client_visible)}><span></span>Visible para cliente</label>
        <div class="im-formulario__acciones im-pm-form__acciones"><button class="im-boton im-boton--tonal" type="submit">Guardar objetivo</button><button class="im-boton im-boton--texto" type="button" data-pm-toggle="#${safeId('pm-objetivo-form', objetivo.id)}">Cancelar</button></div>
      </form>
    `;

    const renderObjetivo = (projectId, objetivo, fasesProyecto) => `
      <article class="im-pm-objetivo">
        <div class="im-pm-objetivo__cabecera">
          <strong>${escapeHtml(objetivo.title)}</strong>
          <button class="im-boton-icono material-symbols-rounded" type="button" data-pm-toggle="#${safeId('pm-objetivo-form', objetivo.id)}" aria-label="Editar objetivo">edit</button>
        </div>
        <div class="im-pm-chips">
          <span class="im-chip">${escapeHtml(tipoLabel(objetivo.deliverable_type))}</span>
          <span class="im-chip im-pm-estado--${escapeHtml(objetivo.status || 'pending')}">${escapeHtml(estadoLabel(objetivo.status))}</span>
          <span class="im-chip ${Number(objetivo.client_visible || 0) === 1 ? 'im-chip--exito' : 'im-chip--alerta'}">${Number(objetivo.client_visible || 0) === 1 ? 'Visible cliente' : 'Oculto cliente'}</span>
        </div>
        <p>${escapeHtml(texto(objetivo.description, 'Sin descripcion'))}</p>
        <small>Fecha limite: ${fecha(objetivo.due_date)}</small>
        ${renderObjetivoForm(projectId, objetivo, fasesProyecto)}
      </article>
    `;

    const renderNuevoObjetivoForm = (projectId, fase) => `
      <form class="im-pm-form im-pm-form--compacto im-pm-form--objetivo" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php" id="${safeId('pm-nuevo-objetivo', fase.id)}" hidden>
        <input type="hidden" name="accion_proyecto" value="pm_crear_objetivo">
        <input type="hidden" name="project_id" value="${Number(projectId)}">
        <input type="hidden" name="phase_id" value="${Number(fase.id)}">
        <label class="im-campo im-campo-material im-campo--ancho"><span>Nuevo objetivo</span><input type="text" name="title" maxlength="180" required></label>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="2"></textarea></label>
        <label class="im-campo im-campo-material"><span>Tipo</span><select name="deliverable_type"><option value="document">Documento</option><option value="design">Diseno</option><option value="development">Desarrollo</option><option value="deployment">Publicacion</option><option value="training">Capacitacion</option><option value="other">Otro</option></select></label>
        <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending">Pendiente</option><option value="in_progress">En progreso</option><option value="ready_for_review">Listo para revision</option><option value="delivered">Entregado</option></select></label>
        <label class="im-campo im-campo-material"><span>Fecha limite</span><input type="date" name="due_date"></label>
        <label class="im-slide-toggle im-pm-toggle"><input type="checkbox" name="client_visible" checked><span></span>Visible para cliente</label>
        <div class="im-formulario__acciones im-pm-form__acciones"><button class="im-boton im-boton--principal" type="submit">Guardar objetivo</button><button class="im-boton im-boton--texto" type="button" data-pm-toggle="#${safeId('pm-nuevo-objetivo', fase.id)}">Cancelar</button></div>
      </form>
    `;

    const renderFase = (projectId, fase, fasesProyecto) => {
      const objetivosFase = (objetivos[projectId] || []).filter((objetivo) => Number(objetivo.phase_id) === Number(fase.id));
      return `
        <article class="im-pm-fase">
          <header class="im-pm-fase__cabecera">
            <div>
              <span class="im-chip im-pm-estado--${escapeHtml(fase.status || 'pending')}">${escapeHtml(estadoLabel(fase.status))}</span>
              <h5>${escapeHtml(fase.title)}</h5>
              <p>${escapeHtml(texto(fase.description, 'Sin descripcion'))}</p>
            </div>
            <button class="im-boton-icono material-symbols-rounded" type="button" data-pm-toggle="#${safeId('pm-fase-form', fase.id)}" aria-label="Editar fase">edit</button>
          </header>
          <div class="im-pm-fase__meta">
            <span>Fecha: ${fecha(fase.due_date)}</span>
            <span>Duracion: ${fase.duration_days ? `${Number(fase.duration_days)} dias` : 'Sin cargar'}</span>
            <span>Orden: ${Number(fase.phase_order || 1)}</span>
            <span>${objetivosFase.length} objetivos</span>
          </div>
          <form class="im-pm-form im-pm-form--compacto" method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php" id="${safeId('pm-fase-form', fase.id)}" hidden>
            <input type="hidden" name="accion_proyecto" value="pm_editar_fase">
            <input type="hidden" name="project_id" value="${Number(projectId)}">
            <input type="hidden" name="phase_id" value="${Number(fase.id)}">
            <label class="im-campo im-campo-material im-campo--ancho"><span>Fase</span><input type="text" name="title" maxlength="180" value="${escapeHtml(fase.title)}" required></label>
            <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="2">${escapeHtml(fase.description || '')}</textarea></label>
            <label class="im-campo im-campo-material"><span>Orden</span><input type="number" name="phase_order" min="1" value="${Number(fase.phase_order || 1)}" required></label>
            <label class="im-campo im-campo-material"><span>Duracion dias</span><input type="number" name="duration_days" min="1" value="${escapeHtml(fase.duration_days || '')}"></label>
            <label class="im-campo im-campo-material"><span>Fecha estimada</span><input type="date" name="due_date" value="${escapeHtml(fase.due_date || '')}"></label>
            <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending"${selected(fase.status, 'pending')}>Pendiente</option><option value="in_progress"${selected(fase.status, 'in_progress')}>En progreso</option><option value="blocked"${selected(fase.status, 'blocked')}>Bloqueada</option><option value="done"${selected(fase.status, 'done')}>Finalizada</option></select></label>
            <div class="im-formulario__acciones im-pm-form__acciones"><button class="im-boton im-boton--tonal" type="submit">Guardar fase</button><button class="im-boton im-boton--texto" type="button" data-pm-toggle="#${safeId('pm-fase-form', fase.id)}">Cancelar</button></div>
          </form>
          <div class="im-pm-objetivos">
            <div class="im-pm-objetivos__titulo">Objetivos</div>
            ${objetivosFase.map((objetivo) => renderObjetivo(projectId, objetivo, fasesProyecto)).join('') || '<div class="im-alerta im-alerta--info">Sin objetivos todavia.</div>'}
          </div>
          ${renderNuevoObjetivoForm(projectId, fase)}
          <button class="im-boton im-boton--texto im-pm-agregar" type="button" data-pm-toggle="#${safeId('pm-nuevo-objetivo', fase.id)}">
            <span class="material-symbols-rounded" aria-hidden="true">add</span>
            ${objetivosFase.length ? 'Nuevo objetivo' : 'Agregar primer objetivo'}
          </button>
        </article>
      `;
    };

    const abrirProyecto = (projectId) => {
      const proyecto = proyectosPorId.get(String(projectId));
      if (!proyecto) {
        return;
      }
      const fasesProyecto = fases[projectId] || [];
      if (formNuevaFase) {
        formNuevaFase.hidden = true;
        formNuevaFase.reset();
      }
      inputFaseProject.value = projectId;
      subtitulo.textContent = `Proyecto #${projectId}`;
      nombreProyecto.textContent = proyecto.project_name || `Proyecto #${projectId}`;
      contextoProyecto.textContent = [
        proyecto.client_name ? `Cliente: ${proyecto.client_name}` : '',
        proyecto.manager_correo ? `Responsable: ${proyecto.manager_correo}` : '',
        proyecto.source_type ? `Origen: ${proyecto.source_type}${proyecto.source_id ? ` #${proyecto.source_id}` : ''}` : ''
      ].filter(Boolean).join(' | ');
      setProjectForm(proyecto);
      renderProgreso(projectId, proyecto);
      tablero.innerHTML = fasesProyecto.length
        ? fasesProyecto.map((fase) => renderFase(projectId, fase, fasesProyecto)).join('')
        : '<div class="im-pm-vacio"><span class="material-symbols-rounded" aria-hidden="true">account_tree</span><strong>Este proyecto todavia no tiene fases cargadas.</strong><p>Crea la primera fase para organizar sus objetivos.</p></div>';
      alternar(true);
    };

    modal.addEventListener('click', (event) => {
      const botonToggle = event.target.closest('[data-pm-toggle]');
      if (!botonToggle) {
        return;
      }
      const destino = document.querySelector(botonToggle.dataset.pmToggle);
      if (destino) {
        destino.hidden = !destino.hidden;
      }
    });

    document.querySelectorAll('[data-abrir-gestor-proyecto]').forEach((boton) => {
      boton.addEventListener('click', () => abrirProyecto(boton.dataset.abrirGestorProyecto));
    });

    document.querySelectorAll('[data-cerrar-gestor-proyecto]').forEach((elemento) => elemento.addEventListener('click', () => alternar(false)));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') alternar(false); });
  })();
</script>
