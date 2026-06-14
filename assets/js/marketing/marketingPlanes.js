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

    const trigger = event.target.closest('[data-marketing-load-plan]');
    if (!trigger) {
      return;
    }

    const form = document.querySelector('[data-marketing-plan-form]');
    if (!form) {
      return;
    }

    const plan = JSON.parse(trigger.dataset.marketingLoadPlan || '{}');
    Object.entries(plan).forEach(([key, value]) => {
      const field = form.elements[key];
      if (!field) {
        return;
      }
      if (field.type === 'checkbox') {
        field.checked = String(value) === '1';
      } else {
        field.value = value ?? '';
      }
    });
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

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
