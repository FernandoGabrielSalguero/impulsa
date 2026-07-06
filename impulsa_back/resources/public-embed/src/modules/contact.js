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
  logger.set('contact', 'ready', '—', 'submit via Impulsa.contact.submit()');

  return api;
}

function bindForms(api) {
  document.querySelectorAll('form[data-impulsa-contact]').forEach((form) => {
    if (form.dataset.impulsaBound === '1') return;
    form.dataset.impulsaBound = '1';

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const formData = new FormData(form);

      const payload = {
        contact_nombre: String(formData.get('contact_nombre') || formData.get('nombre') || ''),
        contact_email: String(formData.get('contact_email') || formData.get('email') || ''),
        contact_whatsapp: String(formData.get('contact_whatsapp') || formData.get('whatsapp') || ''),
        contact_description: String(formData.get('contact_description') || formData.get('mensaje') || ''),
        contact_consultation: String(formData.get('contact_consultation') || formData.get('consulta') || ''),
      };

      try {
        await api.submit(payload);
        form.dispatchEvent(new CustomEvent('impulsa:contact:success', { bubbles: true }));
      } catch (error) {
        form.dispatchEvent(
          new CustomEvent('impulsa:contact:error', { bubbles: true, detail: error }),
        );
      }
    });
  });
}
