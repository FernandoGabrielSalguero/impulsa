export async function initSubscription(http, logger, ownerContact) {
  try {
    const payload = await http.request('/subscription-status');
    const data = payload?.data || {};

    if (data.access_allowed === false) {
      showBlockModal(data, ownerContact);
      logger.set('subscription', 'blocked', '—', `${data.status || 'overdue'}, modal shown`);
    } else {
      logger.set('subscription', 'ok', '—', data.status || 'active');
    }

    return data;
  } catch (error) {
    logger.set('subscription', 'error', '—', error.message);
    return null;
  }
}

function showBlockModal(data, ownerContact) {
  if (document.getElementById('impulsa-subscription-block')) {
    return;
  }

  const overlay = document.createElement('div');
  overlay.id = 'impulsa-subscription-block';
  overlay.setAttribute('role', 'dialog');
  overlay.setAttribute('aria-modal', 'true');
  overlay.style.cssText = [
    'position:fixed',
    'inset:0',
    'z-index:2147483647',
    'background:rgba(15,23,42,0.92)',
    'display:flex',
    'align-items:center',
    'justify-content:center',
    'padding:24px',
    'font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif',
  ].join(';');

  const card = document.createElement('div');
  card.style.cssText = [
    'max-width:520px',
    'width:100%',
    'background:#fff',
    'border-radius:16px',
    'padding:28px',
    'text-align:center',
    'box-shadow:0 20px 60px rgba(0,0,0,0.35)',
  ].join(';');

  const title = document.createElement('h2');
  title.textContent = 'Sitio temporalmente no disponible';
  title.style.cssText = 'margin:0 0 12px;font-size:22px;color:#0f172a';

  const message = document.createElement('p');
  message.textContent =
    data.message ||
    'Estamos experimentando inconvenientes técnicos. Contacte al administrador del sitio.';
  message.style.cssText = 'margin:0 0 16px;color:#475569;line-height:1.5';

  card.appendChild(title);
  card.appendChild(message);

  const contact = ownerContact || data.owner_contact;
  if (contact && (contact.name || contact.email || contact.whatsapp)) {
    const contactEl = document.createElement('p');
    contactEl.style.cssText = 'margin:0 0 16px;color:#334155;line-height:1.5;font-size:14px';
    const parts = [];
    if (contact.name) parts.push(contact.name);
    if (contact.email) parts.push(contact.email);
    if (contact.whatsapp) parts.push(`WhatsApp: ${contact.whatsapp}`);
    contactEl.textContent = `Contacto del dueño: ${parts.join(' · ')}`;
    card.appendChild(contactEl);
  }

  if (data.payment_url) {
    const payLink = document.createElement('a');
    payLink.href = data.payment_url;
    payLink.target = '_blank';
    payLink.rel = 'noopener noreferrer';
    payLink.textContent = 'Regularizar suscripción en Mercado Pago';
    payLink.style.cssText = [
      'display:inline-block',
      'margin-top:8px',
      'padding:12px 18px',
      'background:#009ee3',
      'color:#fff',
      'text-decoration:none',
      'border-radius:10px',
      'font-weight:600',
    ].join(';');
    card.appendChild(payLink);
  }

  overlay.appendChild(card);
  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';
}
