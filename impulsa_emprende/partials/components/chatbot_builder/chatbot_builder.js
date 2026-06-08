(function () {
  const seedNode = () => ({
    client_key: 'node-' + Date.now() + '-' + Math.floor(Math.random() * 1000),
    title: '',
    body: '',
    sort_order: 1,
    status: 'active',
    is_start: false,
    options: [
      {
        label: '',
        action_type: 'go_to_node',
        target_node_key: '',
        sort_order: 1
      }
    ]
  });

  const seedOption = () => ({
    label: '',
    action_type: 'go_to_node',
    target_node_key: '',
    sort_order: 1
  });

  const escapeHtml = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');

  const initBuilder = () => {
    const form = document.querySelector('[data-chatbot-builder-form]');
    if (!form) {
      return;
    }

    const nodesContainer = form.querySelector('[data-chatbot-builder-nodes]');
    const nodesJsonInput = form.querySelector('[data-chatbot-builder-nodes-json]');
    const addNodeButton = form.querySelector('[data-chatbot-builder-add-node]');
    const seedScript = document.querySelector('[data-chatbot-builder-seed]');
    let nodes = [];

    if (seedScript) {
      try {
        const parsed = JSON.parse(seedScript.textContent || '[]');
        if (Array.isArray(parsed)) {
          nodes = parsed;
        }
      } catch (error) {
        nodes = [];
      }
    }

    if (!Array.isArray(nodes) || nodes.length === 0) {
      nodes = [seedNode()];
      nodes[0].title = 'Inicio';
      nodes[0].body = 'Bienvenido. Elegi una opcion para continuar.';
      nodes[0].is_start = true;
    }

    const nodeOptionsMarkup = (currentKey) => {
      return nodes
        .map((node) => `<option value="${escapeHtml(node.client_key)}" ${node.client_key === currentKey ? 'selected' : ''}>${escapeHtml(node.title || node.client_key)}</option>`)
        .join('');
    };

    const render = () => {
      nodes.forEach((node, index) => {
        node.sort_order = index + 1;
        if (!Array.isArray(node.options) || node.options.length === 0) {
          node.options = [seedOption()];
        }
        node.options.forEach((option, optionIndex) => {
          option.sort_order = optionIndex + 1;
        });
      });

      if (!nodes.some((node) => node.is_start)) {
        nodes[0].is_start = true;
      }

      nodesContainer.innerHTML = nodes.map((node, index) => `
        <article class="im-tarjeta" data-node-key="${escapeHtml(node.client_key)}" style="margin-top:1rem">
          <div class="im-tarjeta__cabecera">
            <div>
              <h4>Nodo ${index + 1}</h4>
              <p>Orden ${node.sort_order}</p>
            </div>
            <button class="im-boton im-boton--texto" type="button" data-remove-node="${escapeHtml(node.client_key)}" ${nodes.length === 1 ? 'disabled' : ''}>Eliminar nodo</button>
          </div>
          <div class="im-grilla im-grilla--dos-columnas">
            <label class="im-campo im-campo-material">
              <span>Titulo o pregunta</span>
              <input type="text" value="${escapeHtml(node.title)}" data-node-field="title" data-node-key="${escapeHtml(node.client_key)}" maxlength="180" required>
            </label>
            <label class="im-campo im-campo-material">
              <span>Estado</span>
              <select data-node-field="status" data-node-key="${escapeHtml(node.client_key)}">
                <option value="active" ${node.status === 'active' ? 'selected' : ''}>Activo</option>
                <option value="inactive" ${node.status === 'inactive' ? 'selected' : ''}>Inactivo</option>
              </select>
            </label>
            <label class="im-campo im-campo-material im-campo--ancho">
              <span>Respuesta</span>
              <textarea rows="3" maxlength="2000" data-node-field="body" data-node-key="${escapeHtml(node.client_key)}" required>${escapeHtml(node.body)}</textarea>
            </label>
          </div>
          <label class="im-slide-toggle im-campo--ancho" style="margin-top:.75rem">
            <input type="radio" name="chatbot_start_node" value="${escapeHtml(node.client_key)}" ${node.is_start ? 'checked' : ''} data-node-start="${escapeHtml(node.client_key)}">
            <span></span> Usar como nodo inicial
          </label>
          <div style="margin-top:1rem">
            <div class="im-tarjeta__cabecera">
              <div>
                <h4>Opciones</h4>
                <p>Botones visibles dentro de este nodo.</p>
              </div>
              <button class="im-boton im-boton--tonal" type="button" data-add-option="${escapeHtml(node.client_key)}">Agregar opcion</button>
            </div>
            ${node.options.map((option, optionIndex) => `
              <div class="im-grilla im-grilla--dos-columnas" style="margin-top:.75rem" data-option-index="${optionIndex}">
                <label class="im-campo im-campo-material">
                  <span>Texto del boton</span>
                  <input type="text" value="${escapeHtml(option.label)}" data-option-field="label" data-node-key="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}" maxlength="180" required>
                </label>
                <label class="im-campo im-campo-material">
                  <span>Accion</span>
                  <select data-option-field="action_type" data-node-key="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}">
                    <option value="go_to_node" ${option.action_type === 'go_to_node' ? 'selected' : ''}>Ir a otro nodo</option>
                    <option value="whatsapp" ${option.action_type === 'whatsapp' ? 'selected' : ''}>Abrir WhatsApp</option>
                    <option value="restart" ${option.action_type === 'restart' ? 'selected' : ''}>Reiniciar</option>
                    <option value="close" ${option.action_type === 'close' ? 'selected' : ''}>Cerrar</option>
                  </select>
                </label>
                <label class="im-campo im-campo-material ${option.action_type === 'go_to_node' ? '' : 'im-campo--deshabilitado'}">
                  <span>Nodo destino</span>
                  <select data-option-field="target_node_key" data-node-key="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}" ${option.action_type === 'go_to_node' ? '' : 'disabled'}>
                    <option value="">Seleccionar</option>
                    ${nodeOptionsMarkup(option.target_node_key)}
                  </select>
                </label>
                <div class="im-formulario__acciones" style="align-items:end">
                  <button class="im-boton im-boton--texto" type="button" data-remove-option="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}" ${node.options.length === 1 ? 'disabled' : ''}>Eliminar opcion</button>
                </div>
              </div>
            `).join('')}
          </div>
        </article>
      `).join('');

      nodesJsonInput.value = JSON.stringify(nodes);
    };

    const findNode = (key) => nodes.find((node) => node.client_key === key);

    addNodeButton?.addEventListener('click', () => {
      const node = seedNode();
      node.sort_order = nodes.length + 1;
      nodes.push(node);
      render();
    });

    nodesContainer.addEventListener('click', (event) => {
      const removeNodeButton = event.target.closest('[data-remove-node]');
      if (removeNodeButton) {
        const key = removeNodeButton.getAttribute('data-remove-node');
        nodes = nodes.filter((node) => node.client_key !== key);
        if (!nodes.some((node) => node.is_start) && nodes[0]) {
          nodes[0].is_start = true;
        }
        render();
        return;
      }

      const addOptionButton = event.target.closest('[data-add-option]');
      if (addOptionButton) {
        const node = findNode(addOptionButton.getAttribute('data-add-option'));
        if (!node) {
          return;
        }
        node.options.push(seedOption());
        render();
        return;
      }

      const removeOptionButton = event.target.closest('[data-remove-option]');
      if (removeOptionButton) {
        const node = findNode(removeOptionButton.getAttribute('data-remove-option'));
        const optionIndex = Number(removeOptionButton.getAttribute('data-option-index'));
        if (!node || !Number.isInteger(optionIndex)) {
          return;
        }
        node.options = node.options.filter((_, index) => index !== optionIndex);
        render();
      }
    });

    nodesContainer.addEventListener('input', (event) => {
      const target = event.target;
      if (!(target instanceof HTMLElement)) {
        return;
      }

      if (target.matches('[data-node-field]')) {
        const input = target;
        const node = findNode(input.getAttribute('data-node-key'));
        if (!node) {
          return;
        }
        node[input.getAttribute('data-node-field')] = input.value;
        nodesJsonInput.value = JSON.stringify(nodes);
      }

      if (target.matches('[data-option-field]')) {
        const input = target;
        const node = findNode(input.getAttribute('data-node-key'));
        const optionIndex = Number(input.getAttribute('data-option-index'));
        if (!node || !node.options[optionIndex]) {
          return;
        }
        node.options[optionIndex][input.getAttribute('data-option-field')] = input.value;
        if (input.getAttribute('data-option-field') === 'action_type') {
          if (input.value !== 'go_to_node') {
            node.options[optionIndex].target_node_key = '';
          }
          render();
          return;
        }
        nodesJsonInput.value = JSON.stringify(nodes);
      }
    });

    nodesContainer.addEventListener('change', (event) => {
      const target = event.target;
      if (!(target instanceof HTMLElement)) {
        return;
      }

      if (target.matches('[data-node-start]')) {
        const key = target.getAttribute('data-node-start');
        nodes = nodes.map((node) => ({
          ...node,
          is_start: node.client_key === key
        }));
        render();
      }
    });

    form.addEventListener('submit', () => {
      nodesJsonInput.value = JSON.stringify(nodes);
    });

    render();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBuilder);
  } else {
    initBuilder();
  }
})();
