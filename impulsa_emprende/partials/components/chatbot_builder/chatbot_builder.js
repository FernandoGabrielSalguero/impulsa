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
    const avatarInput = form.querySelector('[data-chatbot-avatar-input]');
    const avatarPreview = form.querySelector('[data-chatbot-avatar-preview]');
    const avatarFileName = form.querySelector('[data-chatbot-avatar-filename]');
    const previewAvatar = form.querySelector('[data-chatbot-preview-avatar]');
    const previewName = form.querySelector('[data-chatbot-preview-name]');
    const previewThread = form.querySelector('[data-chatbot-preview-thread]');
    const previewMessage = form.querySelector('[data-chatbot-preview-message]');
    const previewOptions = form.querySelector('[data-chatbot-preview-options]');
    const previewResetButton = form.querySelector('[data-chatbot-preview-reset]');
    let nodes = [];
    let currentPreviewNodeKey = '';
    let previewHistory = [];

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

    const nodeOptionsMarkup = (currentKey) => nodes
      .map((node) => `<option value="${escapeHtml(node.client_key)}" ${node.client_key === currentKey ? 'selected' : ''}>${escapeHtml(node.title || node.client_key)}</option>`)
      .join('');

    const getStartNode = () => nodes.find((node) => node.is_start) || nodes[0] || null;

    const getPreviewNode = () => {
      if (currentPreviewNodeKey === '__closed__') {
        return null;
      }
      const current = nodes.find((node) => node.client_key === currentPreviewNodeKey);
      return current || getStartNode();
    };

    const resetPreview = () => {
      const startNode = getStartNode();
      currentPreviewNodeKey = startNode ? startNode.client_key : '';
      previewHistory = [];
    };

    const updatePreview = () => {
      const nameField = form.querySelector('[name="name"]');
      const messageField = form.querySelector('[name="initial_message"]');
      const previewNode = getPreviewNode();

      if (previewName) {
        previewName.textContent = (nameField?.value || 'Chatbot').trim() || 'Chatbot';
      }

      if (previewMessage) {
        previewMessage.textContent = (messageField?.value || 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.').trim();
      }

      if (previewThread) {
        const introMessage = (messageField?.value || 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.').trim();
        previewThread.innerHTML = '';

        const introBubble = document.createElement('div');
        introBubble.className = 'im-alerta im-alerta--info';
        introBubble.textContent = introMessage;
        previewThread.appendChild(introBubble);

        previewHistory.forEach((entry) => {
          const bubble = document.createElement('div');
          bubble.className = entry.role === 'user' ? 'im-chip-lista' : 'im-alerta im-alerta--info';

          if (entry.role === 'user') {
            const chip = document.createElement('span');
            chip.className = 'im-chip';
            chip.textContent = entry.text;
            bubble.appendChild(chip);
          } else {
            bubble.textContent = entry.text;
          }

          previewThread.appendChild(bubble);
        });
      }

      if (previewOptions) {
        previewOptions.innerHTML = '';
        const options = previewNode && Array.isArray(previewNode.options) ? previewNode.options : [];
        options.slice(0, 4).forEach((option) => {
          const item = document.createElement('button');
          item.type = 'button';
          item.className = 'im-boton im-boton--tonal';
          item.textContent = option.label || 'Opcion';
          item.setAttribute('data-preview-action-type', option.action_type || '');
          item.setAttribute('data-preview-target-key', option.target_node_key || '');
          previewOptions.appendChild(item);
        });
      }

      if (previewAvatar && avatarPreview) {
        previewAvatar.innerHTML = avatarPreview.innerHTML;
      }
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

      if (!nodes.some((node) => node.is_start) && nodes[0]) {
        nodes[0].is_start = true;
      }

      if (currentPreviewNodeKey !== '__closed__' && !nodes.some((node) => node.client_key === currentPreviewNodeKey)) {
        resetPreview();
      }

      nodesContainer.innerHTML = nodes.map((node, index) => `
        <details class="im-expansion" data-node-key="${escapeHtml(node.client_key)}" ${index === 0 ? 'open' : ''}>
          <summary>Pregunta ${index + 1} - ${escapeHtml(node.title || 'Sin titulo')}</summary>
          <div class="im-muestra im-muestra--vertical">
            <div class="im-chip-lista">
              ${node.is_start ? '<span class="im-chip im-chip--completado">Inicial</span>' : ''}
              <span class="im-chip ${node.status === 'active' ? 'im-chip--activo' : 'im-chip--alerta'}">${node.status === 'active' ? 'Activa' : 'Inactiva'}</span>
              <span class="im-chip">${node.options.length} ${node.options.length === 1 ? 'opcion' : 'opciones'}</span>
            </div>
            <div class="im-formulario">
              <label class="im-campo im-campo-material">
                <span>Pregunta</span>
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
                <textarea rows="4" maxlength="2000" data-node-field="body" data-node-key="${escapeHtml(node.client_key)}" required>${escapeHtml(node.body)}</textarea>
              </label>
              <fieldset class="im-campo im-campo-grupo im-campo--ancho">
                <legend>Comportamiento del nodo</legend>
                <label class="im-slide-toggle">
                  <input type="radio" name="chatbot_start_node" value="${escapeHtml(node.client_key)}" ${node.is_start ? 'checked' : ''} data-node-start="${escapeHtml(node.client_key)}">
                  <span></span> Usar como pregunta inicial
                </label>
              </fieldset>
              <div class="im-formulario__separador">Opciones y vinculaciones</div>
              <div class="im-campo--ancho">
                <div class="im-tarjeta__cabecera">
                  <div>
                    <h4>Botones del nodo</h4>
                    <p>Define que ve el usuario y que accion se dispara despues.</p>
                  </div>
                  <button class="im-boton im-boton--tonal" type="button" data-add-option="${escapeHtml(node.client_key)}">Agregar opcion</button>
                </div>
              </div>
              <div class="im-muestra im-muestra--vertical im-campo--ancho">
                ${node.options.map((option, optionIndex) => `
                  <article class="im-tarjeta" data-option-index="${optionIndex}">
                    <div class="im-formulario">
                      <label class="im-campo im-campo-material">
                        <span>Texto del boton</span>
                        <input type="text" value="${escapeHtml(option.label)}" data-option-field="label" data-node-key="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}" maxlength="180" required>
                      </label>
                      <label class="im-campo im-campo-material">
                        <span>Accion al hacer clic</span>
                        <select data-option-field="action_type" data-node-key="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}">
                          <option value="go_to_node" ${option.action_type === 'go_to_node' ? 'selected' : ''}>Ir a otra pregunta</option>
                          <option value="whatsapp" ${option.action_type === 'whatsapp' ? 'selected' : ''}>Abrir WhatsApp</option>
                          <option value="restart" ${option.action_type === 'restart' ? 'selected' : ''}>Volver al inicio</option>
                          <option value="close" ${option.action_type === 'close' ? 'selected' : ''}>Cerrar chat</option>
                        </select>
                      </label>
                      <label class="im-campo im-campo-material im-campo--ancho" ${option.action_type === 'go_to_node' ? '' : 'hidden'}>
                        <span>Pregunta destino</span>
                        <select data-option-field="target_node_key" data-node-key="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}" ${option.action_type === 'go_to_node' ? '' : 'disabled'}>
                          <option value="">Seleccionar</option>
                          ${nodeOptionsMarkup(option.target_node_key)}
                        </select>
                      </label>
                      <div class="im-formulario__acciones">
                        <button class="im-boton im-boton--texto" type="button" data-remove-option="${escapeHtml(node.client_key)}" data-option-index="${optionIndex}" ${node.options.length === 1 ? 'disabled' : ''}>Eliminar opcion</button>
                      </div>
                    </div>
                  </article>
                `).join('')}
              </div>
              <div class="im-formulario__acciones">
                <button class="im-boton im-boton--texto" type="button" data-toggle-question="${escapeHtml(node.client_key)}">Abrir nodo</button>
                <button class="im-boton im-boton--texto" type="button" data-remove-node="${escapeHtml(node.client_key)}" ${nodes.length === 1 ? 'disabled' : ''}>Eliminar nodo</button>
              </div>
            </div>
          </div>
        </details>
      `).join('');

      nodesJsonInput.value = JSON.stringify(nodes);
      updatePreview();
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
        return;
      }

      const toggleQuestionButton = event.target.closest('[data-toggle-question]');
      if (toggleQuestionButton) {
        const details = toggleQuestionButton.closest('details');
        if (details) {
          details.open = true;
        }
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
        updatePreview();
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
        updatePreview();
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
        resetPreview();
        render();
      }
    });

    previewOptions?.addEventListener('click', (event) => {
      const button = event.target.closest('[data-preview-action-type]');
      if (!button) {
        return;
      }

      const actionType = button.getAttribute('data-preview-action-type');
      const targetKey = button.getAttribute('data-preview-target-key');
      const buttonLabel = (button.textContent || 'Opcion').trim();

      previewHistory.push({
        role: 'user',
        text: buttonLabel
      });

      if (actionType === 'go_to_node' && targetKey) {
        currentPreviewNodeKey = targetKey;
        const targetNode = nodes.find((node) => node.client_key === targetKey);
        if (targetNode) {
          previewHistory.push({
            role: 'bot',
            text: (targetNode.body || targetNode.title || 'Sin respuesta cargada.').trim()
          });
        }
        updatePreview();
        return;
      }

      if (actionType === 'restart') {
        resetPreview();
        updatePreview();
        return;
      }

      if (actionType === 'close') {
        previewHistory.push({
          role: 'bot',
          text: 'La conversacion termino en esta vista previa.'
        });
        currentPreviewNodeKey = '__closed__';
        updatePreview();
        return;
      }

      if (actionType === 'whatsapp') {
        previewHistory.push({
          role: 'bot',
          text: 'Esta opcion abrira WhatsApp en el widget real.'
        });
        updatePreview();
      }
    });

    previewResetButton?.addEventListener('click', () => {
      resetPreview();
      updatePreview();
    });

    form.querySelectorAll('[name="name"], [name="initial_message"]').forEach((field) => {
      field.addEventListener('input', updatePreview);
    });

    if (avatarInput && avatarPreview) {
      avatarInput.addEventListener('change', () => {
        const file = avatarInput.files && avatarInput.files[0] ? avatarInput.files[0] : null;
        if (avatarFileName) {
          avatarFileName.textContent = file ? file.name : 'Sin imagen cargada';
        }
        if (!file) {
          updatePreview();
          return;
        }

        const reader = new FileReader();
        reader.onload = () => {
          avatarPreview.innerHTML = `<img src="${escapeHtml(reader.result)}" alt="" width="96" height="96">`;
          updatePreview();
        };
        reader.readAsDataURL(file);
      });
    }

    form.addEventListener('submit', () => {
      nodesJsonInput.value = JSON.stringify(nodes);
    });

    resetPreview();
    render();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBuilder);
  } else {
    initBuilder();
  }
})();
