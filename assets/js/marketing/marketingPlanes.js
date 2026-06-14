(function () {
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-marketing-snackbar]').forEach((snackbar) => {
      const message = (snackbar.dataset.marketingSnackbar || '').trim();
      if (!message) {
        return;
      }
      const text = snackbar.querySelector('span');
      if (text) {
        text.textContent = message;
      }
      snackbar.classList.add('abierto');
    });
  });

  const setActiveTab = (tabs, index) => {
    const buttons = [...tabs.querySelectorAll(':scope > button')];
    const panels = [...tabs.parentElement.querySelectorAll(':scope > .im-tab-panel')];
    buttons.forEach((button, buttonIndex) => button.classList.toggle('activo', buttonIndex === index));
    panels.forEach((panel, panelIndex) => panel.classList.toggle('activo', panelIndex === index));
  };

  document.querySelectorAll('[data-marketing-tabs]').forEach((tabs) => {
    [...tabs.querySelectorAll(':scope > button')].forEach((button, index) => {
      button.addEventListener('click', () => setActiveTab(tabs, index));
    });
  });

  const planForm = document.querySelector('[data-marketing-plan-form]');
  const featureList = document.querySelector('[data-marketing-feature-list]');
  const pricingList = document.querySelector('[data-marketing-pricing-list]');
  const featureEditor = document.querySelector('[data-marketing-feature-editor]');
  const pricingEditor = document.querySelector('[data-marketing-pricing-editor]');
  const plansModal = document.querySelector('[data-marketing-plans-modal]');
  let features = parseJson(planForm?.dataset.initialFeatures, []);
  let pricing = parseJson(planForm?.dataset.initialPricing, []);
  let editingFeatureIndex = null;
  let editingPricingIndex = null;

  renderFeatures();
  renderPricing();

  pricingEditor?.addEventListener('input', (event) => {
    if (event.target.matches('[data-pricing-field="duration_months"], [data-pricing-field="monthly_price"]')) {
      updatePricingTotal();
    }
  });
  pricingEditor?.addEventListener('change', (event) => {
    if (event.target.matches('[data-pricing-field="duration_months"], [data-pricing-field="monthly_price"]')) {
      updatePricingTotal();
    }
  });

  planForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submitter = event.submitter;
    if (!submitter?.matches('[data-marketing-delete-plan]')) {
      syncOpenEditors();
    }
    await submitPlanForm(submitter);
  });

  document.addEventListener('click', (event) => {
    const snackbarClose = event.target.closest('[data-cerrar-snackbar]');
    if (snackbarClose) {
      snackbarClose.closest('.im-snackbar')?.classList.remove('abierto');
    }

    const confirmButton = event.target.closest('[data-marketing-confirm]');
    if (confirmButton && !window.confirm(confirmButton.dataset.marketingConfirm || 'Confirmar accion?')) {
      event.preventDefault();
      return;
    }

    if (event.target.closest('[data-marketing-open-plans]')) {
      plansModal?.classList.add('abierto');
      plansModal?.setAttribute('aria-hidden', 'false');
      return;
    }

    if (event.target.closest('[data-marketing-close-plans]')) {
      closePlansModal();
      return;
    }

    if (event.target.closest('[data-marketing-new-plan]')) {
      resetPlanForm();
      closePlansModal();
      return;
    }

    const addFeature = event.target.closest('[data-marketing-add-feature]');
    if (addFeature) {
      upsertFeature();
      return;
    }

    const addPricing = event.target.closest('[data-marketing-add-pricing]');
    if (addPricing) {
      upsertPricing();
      return;
    }

    const editFeature = event.target.closest('[data-marketing-edit-feature]');
    if (editFeature) {
      loadFeatureEditor(Number(editFeature.dataset.marketingEditFeature));
      return;
    }

    const deleteFeature = event.target.closest('[data-marketing-delete-feature]');
    if (deleteFeature) {
      features.splice(Number(deleteFeature.dataset.marketingDeleteFeature), 1);
      renderFeatures();
      return;
    }

    const editPricing = event.target.closest('[data-marketing-edit-pricing]');
    if (editPricing) {
      loadPricingEditor(Number(editPricing.dataset.marketingEditPricing));
      return;
    }

    const deletePricing = event.target.closest('[data-marketing-delete-pricing]');
    if (deletePricing) {
      pricing.splice(Number(deletePricing.dataset.marketingDeletePricing), 1);
      renderPricing();
      return;
    }

    const trigger = event.target.closest('[data-marketing-load-plan]');
    if (!trigger || !planForm) {
      return;
    }

    const plan = JSON.parse(trigger.dataset.marketingLoadPlan || '{}');
    loadPlan(plan);
  });

  function closePlansModal() {
    plansModal?.classList.remove('abierto');
    plansModal?.setAttribute('aria-hidden', 'true');
  }

  function loadPlan(plan, options = {}) {
    Object.entries(plan).forEach(([key, value]) => {
      const field = planForm.elements[key];
      if (!field) {
        return;
      }
      if (field.type === 'checkbox') {
        field.checked = String(value) === '1';
      } else {
        field.value = value ?? '';
      }
    });
    planForm.elements.plan_id.value = plan.id || plan.plan_id || '';
    planForm.querySelector('input[name="marketing_action"]').value = 'plan_save_full';
    features = Array.isArray(plan.features) ? plan.features : [];
    pricing = Array.isArray(plan.pricing_options) ? plan.pricing_options : [];
    editingFeatureIndex = null;
    editingPricingIndex = null;
    renderFeatures();
    renderPricing();
    setBuilderMode(Boolean(Number(plan.id || plan.plan_id || 0)));
    if (options.closeModal !== false) {
      closePlansModal();
    }
    if (options.scroll !== false) {
      planForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function resetPlanForm() {
    if (!planForm) {
      return;
    }
    [...planForm.elements].forEach((field) => {
      if (!field.name || field.name === 'marketing_action' || field.name === 'plan_id') {
        return;
      }
      if (field.type === 'checkbox') {
        field.checked = false;
      } else if (field.tagName === 'SELECT') {
        field.selectedIndex = 0;
      } else if (field.type !== 'button' && field.type !== 'submit') {
        field.value = '';
      }
    });
    planForm.elements.plan_id.value = '';
    planForm.querySelector('input[name="marketing_action"]').value = 'plan_save_full';
    features = [];
    pricing = [];
    editingFeatureIndex = null;
    editingPricingIndex = null;
    renderFeatures();
    renderPricing();
    setBuilderMode(false);
  }

  function setBuilderMode(isEditing) {
    const title = document.querySelector('[data-marketing-builder-title]');
    const subtitle = document.querySelector('[data-marketing-builder-subtitle]');
    const deleteButton = document.querySelector('[data-marketing-delete-plan]');
    if (title) {
      title.textContent = isEditing ? 'Editar plan' : 'Crear plan';
    }
    if (subtitle) {
      subtitle.textContent = isEditing ? 'Estas actualizando un plan existente.' : 'Estas creando un plan nuevo desde cero.';
    }
    if (deleteButton) {
      deleteButton.hidden = !isEditing;
    }
  }

  function upsertFeature() {
    const item = readEditor(featureEditor, 'feature');
    if (!item.feature_name) {
      return;
    }
    item.quantity = integerValue(item.quantity);
    item.feature_order = integerValue(item.feature_order);
    item.is_highlighted = item.is_highlighted ? '1' : '0';
    if (editingFeatureIndex === null) {
      features.push(item);
    } else {
      features[editingFeatureIndex] = item;
    }
    clearEditor(featureEditor, 'feature');
    editingFeatureIndex = null;
    renderFeatures();
  }

  function upsertPricing() {
    const item = readEditor(pricingEditor, 'pricing');
    if (!item.duration_months || !item.monthly_price) {
      return;
    }
    item.duration_months = integerValue(item.duration_months);
    item.monthly_price = integerValue(item.monthly_price);
    item.setup_fee = integerValue(item.setup_fee);
    item.display_order = integerValue(item.display_order);
    item.is_featured = item.is_featured ? '1' : '0';
    item.is_default = item.is_default ? '1' : '0';
    item.total_price = calculatePricingTotal(item.duration_months, item.monthly_price);
    if (editingPricingIndex === null) {
      pricing.push(item);
    } else {
      pricing[editingPricingIndex] = item;
    }
    clearEditor(pricingEditor, 'pricing');
    editingPricingIndex = null;
    renderPricing();
  }

  function loadFeatureEditor(index) {
    const item = features[index];
    if (!item) {
      return;
    }
    writeEditor(featureEditor, 'feature', item);
    editingFeatureIndex = index;
  }

  function loadPricingEditor(index) {
    const item = pricing[index];
    if (!item) {
      return;
    }
    writeEditor(pricingEditor, 'pricing', item);
    updatePricingTotal();
    editingPricingIndex = index;
  }

  function syncOpenEditors() {
    const currentFeature = readEditor(featureEditor, 'feature');
    if (currentFeature.feature_name) {
      upsertFeature();
    }
    const currentPricing = readEditor(pricingEditor, 'pricing');
    if (currentPricing.duration_months && currentPricing.monthly_price) {
      upsertPricing();
    }
  }

  async function submitPlanForm(submitter) {
    if (!planForm) {
      return;
    }

    const formData = new FormData(planForm);
    if (submitter?.name) {
      formData.set(submitter.name, submitter.value);
    }

    setFormBusy(true);
    try {
      const response = await fetch(planForm.action || window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const rawResponse = await response.text();
      let data = {};
      try {
        data = rawResponse ? JSON.parse(rawResponse) : {};
      } catch (error) {
        throw new Error('El servidor no devolvio una respuesta valida.');
      }
      if (!response.ok || !data.ok) {
        throw new Error(data.message || 'No se pudieron guardar los cambios.');
      }

      showMarketingSnackbar(data.message || 'Cambios guardados.', 'ok');
      if (data.deleted) {
        resetPlanForm();
        return;
      }
      if (data.plan) {
        loadPlan(data.plan, { closeModal: false, scroll: false });
      }
    } catch (error) {
      showMarketingSnackbar(error.message || 'No se pudieron guardar los cambios.', 'error');
    } finally {
      setFormBusy(false);
    }
  }

  function setFormBusy(isBusy) {
    planForm?.querySelectorAll('button, input, select, textarea').forEach((field) => {
      if (field.readOnly) {
        return;
      }
      field.disabled = isBusy;
    });
  }

  function showMarketingSnackbar(message, state) {
    const snackbar = document.querySelector('.im-snackbar');
    if (!snackbar) {
      return;
    }
    snackbar.dataset.estado = state === 'error' ? 'error' : 'ok';
    const text = snackbar.querySelector('span');
    if (text) {
      text.textContent = message;
    }
    snackbar.classList.add('abierto');
  }

  function renderFeatures() {
    if (!featureList) {
      return;
    }
    featureList.innerHTML = features.length ? features.map((item, index) => `
      <article class="marketing-builder-list__item">
        ${hiddenInputs('features', index, item, ['id', 'feature_name', 'feature_description', 'quantity', 'unit', 'feature_order', 'is_highlighted'])}
        <div>
          <strong>${escapeHtml(item.feature_name || '')}</strong>
          <p>${escapeHtml([integerValue(item.quantity), item.unit].filter(Boolean).join(' '))}${item.feature_description ? ' - ' + escapeHtml(item.feature_description) : ''}</p>
        </div>
        <div class="marketing-inline-actions">
          ${item.is_highlighted === '1' ? '<span class="im-chip im-chip--exito">Destacado</span>' : ''}
          <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-edit-feature="${index}" aria-label="Editar">edit</button>
          <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-delete-feature="${index}" aria-label="Eliminar">delete</button>
        </div>
      </article>
    `).join('') : emptyBuilder('No agregaste items incluidos.');
  }

  function renderPricing() {
    if (!pricingList) {
      return;
    }
    pricingList.innerHTML = pricing.length ? pricing.map((item, index) => `
      <article class="marketing-builder-list__item">
        ${hiddenInputs('pricing', index, item, ['id', 'duration_months', 'monthly_price', 'total_price', 'setup_fee', 'currency', 'display_order', 'is_featured', 'is_default'])}
        <div>
          <strong>${escapeHtml(integerValue(item.duration_months))} meses - ${escapeHtml(formatMoney(item.monthly_price))}/mes</strong>
          <p>Total ${escapeHtml(formatMoney(item.total_price))} - Costo inicial ${escapeHtml(formatMoney(item.setup_fee))}</p>
        </div>
        <div class="marketing-inline-actions">
          ${item.is_default === '1' ? '<span class="im-chip">Predeterminada</span>' : ''}
          ${item.is_featured === '1' ? '<span class="im-chip im-chip--exito">Destacada</span>' : ''}
          <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-edit-pricing="${index}" aria-label="Editar">edit</button>
          <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-delete-pricing="${index}" aria-label="Eliminar">delete</button>
        </div>
      </article>
    `).join('') : emptyBuilder('No agregaste opciones de precio.');
  }

  function readEditor(container, prefix) {
    const item = {};
    container?.querySelectorAll(`[data-${prefix}-field]`).forEach((field) => {
      const key = field.dataset[`${prefix}Field`];
      item[key] = field.type === 'checkbox' ? (field.checked ? '1' : '0') : field.value.trim();
    });
    return item;
  }

  function writeEditor(container, prefix, item) {
    container?.querySelectorAll(`[data-${prefix}-field]`).forEach((field) => {
      const key = field.dataset[`${prefix}Field`];
      if (field.type === 'checkbox') {
        field.checked = String(item[key] || '0') === '1';
      } else if (['quantity', 'feature_order', 'duration_months', 'display_order', 'monthly_price', 'total_price', 'setup_fee'].includes(key)) {
        field.value = integerValue(item[key]);
      } else {
        field.value = item[key] ?? '';
      }
    });
  }

  function updatePricingTotal() {
    const duration = pricingEditor?.querySelector('[data-pricing-field="duration_months"]')?.value || 0;
    const monthly = pricingEditor?.querySelector('[data-pricing-field="monthly_price"]')?.value || 0;
    const total = pricingEditor?.querySelector('[data-pricing-field="total_price"]');
    if (total) {
      total.value = calculatePricingTotal(duration, monthly);
    }
  }

  function calculatePricingTotal(duration, monthly) {
    const value = Number(integerValue(duration) || 0) * Number(integerValue(monthly) || 0);
    return value ? String(Math.trunc(value)) : '0';
  }

  function integerValue(value) {
    const number = Number(String(value ?? '').replace(',', '.'));
    if (!Number.isFinite(number)) {
      return '';
    }
    return String(Math.trunc(number));
  }

  function formatMoney(value) {
    const number = Number(String(value ?? 0).replace(',', '.'));
    return '$' + (Number.isFinite(number) ? Math.trunc(number).toLocaleString('es-AR', { maximumFractionDigits: 0 }) : '0');
  }

  function clearEditor(container, prefix) {
    container?.querySelectorAll(`[data-${prefix}-field]`).forEach((field) => {
      if (field.type === 'checkbox') {
        field.checked = false;
      } else if (field.tagName === 'SELECT') {
        field.selectedIndex = 0;
      } else {
        field.value = field.dataset[`${prefix}Field`] === 'display_order' || field.dataset[`${prefix}Field`] === 'feature_order' || field.dataset[`${prefix}Field`] === 'setup_fee' ? '0' : '';
      }
    });
  }

  function hiddenInputs(group, index, item, keys) {
    return keys.map((key) => `<input type="hidden" name="${group}[${index}][${key}]" value="${escapeHtml(item[key] ?? '')}">`).join('');
  }

  function emptyBuilder(text) {
    return `<div class="marketing-empty marketing-empty--compact"><span class="material-symbols-rounded">add_circle</span><strong>${escapeHtml(text)}</strong></div>`;
  }

  function parseJson(value, fallback) {
    try {
      return value ? JSON.parse(value) : fallback;
    } catch (error) {
      return fallback;
    }
  }

  const csvInput = document.querySelector('[data-marketing-csv-input]');
  const csvPreview = document.querySelector('[data-marketing-csv-preview]');
  const csvAssignments = document.querySelector('[data-marketing-csv-assignments]');
  if (csvInput && csvPreview && window.Papa) {
    csvInput.addEventListener('change', () => {
      const file = csvInput.files && csvInput.files[0];
      if (!file) {
        csvPreview.innerHTML = '';
        if (csvAssignments) {
          csvAssignments.innerHTML = '';
        }
        return;
      }

      window.Papa.parse(file, {
        header: true,
        preview: 6,
        skipEmptyLines: true,
        complete(results) {
          const fields = results.meta.fields || [];
          const rows = results.data || [];
          if (!fields.length) {
            csvPreview.textContent = 'No se pudieron leer columnas del CSV.';
            return;
          }
          const head = fields.map((field) => `<th>${escapeHtml(field)}</th>`).join('');
          const body = rows.map((row) => `<tr>${fields.map((field) => `<td>${escapeHtml(row[field] ?? '')}</td>`).join('')}</tr>`).join('');
          csvPreview.innerHTML = `<div class="im-tabla-contenedor"><table class="im-tabla"><thead><tr>${head}</tr></thead><tbody>${body}</tbody></table></div>`;
          renderAssignments(rows, fields);
        }
      });
    });
  }

  function renderAssignments(rows, fields) {
    if (!csvAssignments) {
      return;
    }
    const campaignField = fields.find((field) => [
      'Campaign name',
      'Nombre de la campana',
      'Nombre de la campaña',
      'campaign_name'
    ].includes(field));
    const campaigns = JSON.parse(csvAssignments.dataset.marketingCampaignMap || '[]');
    if (!campaignField || !campaigns.length) {
      csvAssignments.innerHTML = '';
      return;
    }
    const names = [...new Set(rows.map((row) => String(row[campaignField] || '').trim()).filter(Boolean))];
    if (!names.length) {
      csvAssignments.innerHTML = '';
      return;
    }
    const options = campaigns.map((campaign) => `<option value="${escapeHtml(campaign.id)}">${escapeHtml(campaign.name)}</option>`).join('');
    csvAssignments.innerHTML = `
      <details class="im-expansion" open>
        <summary>Asignacion manual opcional</summary>
        <div class="marketing-form-grid">
          ${names.map((name) => `
            <label class="im-campo im-campo-material">
              <span class="marketing-field-label">${escapeHtml(name)} <span class="marketing-help-badge im-tooltip" data-tooltip="Campania externa detectada en el CSV. Elegi una campania interna solo si el match automatico no corresponde." aria-label="Campania externa detectada en el CSV. Elegi una campania interna solo si el match automatico no corresponde.">?</span></span>
              <select name="manual_campaign[${escapeHtml(name)}]">
                <option value="">Match automatico</option>
                ${options}
              </select>
            </label>
          `).join('')}
        </div>
      </details>
    `;
  }

  function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (char) => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    }[char]));
  }
})();
