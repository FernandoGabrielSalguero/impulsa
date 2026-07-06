export function initContact(http, logger) {
  const api = {
    submit(payload) {
      const page = `${window.location.pathname || '/'}${window.location.search || ''}`;
      return http.request('/contact-submissions', {
        method: 'POST',
        body: { ...payload, page: payload.page || page },
      });
    },
  };

  bindForms(api);
  logger.set('contact', 'ready', '—', 'form[data-impulsa-contact] required');

  return api;
}

function bindForms(api) {
  document.querySelectorAll('form[data-impulsa-contact]').forEach((form) => {
    bindForm(form, api);
  });

  document.addEventListener(
    'impulsa:bind-contact-forms',
    () => {
      document.querySelectorAll('form[data-impulsa-contact]').forEach((form) => {
        bindForm(form, api);
      });
    },
    false,
  );
}

function bindForm(form, api) {
  if (!(form instanceof HTMLFormElement)) return;
  if (form.dataset.impulsaBound === '1') return;
  form.dataset.impulsaBound = '1';

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    event.stopImmediatePropagation();

    const formData = new FormData(form);
    const payload = buildPayload(formData);
    const feedback = form.querySelector('[data-impulsa-contact-message]');

    if (feedback) {
      feedback.textContent = '';
      feedback.removeAttribute('data-status');
    }

    try {
      await api.submit(payload);

      if (feedback) {
        feedback.textContent = 'Mensaje enviado correctamente.';
        feedback.dataset.status = 'success';
      }

      form.reset();
      form.dispatchEvent(new CustomEvent('impulsa:contact:success', { bubbles: true }));
    } catch (error) {
      if (feedback) {
        feedback.textContent = 'No pudimos enviar el formulario. Intenta nuevamente.';
        feedback.dataset.status = 'error';
      }

      form.dispatchEvent(
        new CustomEvent('impulsa:contact:error', { bubbles: true, detail: error }),
      );
    }
  }, true);
}

function buildPayload(formData) {
  return {
    contact_nombre: pickFormValue(formData, [
      'contact_nombre',
      'nombre',
      'name',
      'nombre_apellido',
      'nombre_y_apellido',
    ]),
    contact_email: pickFormValue(formData, [
      'contact_email',
      'email',
      'correo',
      'correo_electronico',
    ]),
    contact_whatsapp: pickFormValue(formData, [
      'contact_whatsapp',
      'whatsapp',
      'telefono',
      'celular',
    ]),
    contact_description: pickFormValue(formData, [
      'contact_description',
      'mensaje',
      'message',
      'descripcion',
      'comentario',
    ]),
    contact_consultation: pickFormValue(formData, [
      'contact_consultation',
      'consulta',
      'rubro',
      'contact_rubro',
      'rubro_empresa',
    ]),
  };
}

function pickFormValue(formData, keys) {
  for (const key of keys) {
    const value = formData.get(key);

    if (value !== null && String(value).trim() !== '') {
      return String(value).trim();
    }
  }

  return '';
}
