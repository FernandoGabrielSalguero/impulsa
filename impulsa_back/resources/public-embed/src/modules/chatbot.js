const STYLE_ID = 'impulsa-chatbot-styles';

export async function initChatbot(http, logger) {
  try {
    const payload = await http.request('/chatbot');
    const config = payload?.data;

    if (!config) {
      logger.set('chatbot', 'inactive', 0, 'not configured');
      return null;
    }

    mountWidget(http, config);
    await trackEvent(http, 'widget_loaded');

    logger.set('chatbot', 'ok', (config.nodes || []).length, 'widget mounted');

    return {
      config,
      open: () => document.getElementById('impulsa-chatbot-panel')?.classList.add('is-open'),
      trackEvent: (eventType, extra) => trackEvent(http, eventType, extra),
    };
  } catch (error) {
    if (error.status === 404) {
      logger.set('chatbot', 'inactive', 0, 'not available');
      return null;
    }

    logger.set('chatbot', 'error', '—', error.message);
    return null;
  }
}

function ensureStyles() {
  if (document.getElementById(STYLE_ID)) return;

  const style = document.createElement('style');
  style.id = STYLE_ID;
  style.textContent = `
    #impulsa-chatbot-root{position:fixed;right:20px;bottom:20px;z-index:2147483000;font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif}
    #impulsa-chatbot-bubble{width:56px;height:56px;border-radius:50%;border:none;background:#009ee3;color:#fff;font-size:24px;cursor:pointer;box-shadow:0 8px 24px rgba(0,0,0,.2)}
    #impulsa-chatbot-panel{display:none;position:absolute;right:0;bottom:70px;width:320px;max-height:420px;background:#fff;border-radius:16px;box-shadow:0 16px 40px rgba(0,0,0,.18);overflow:hidden;flex-direction:column}
    #impulsa-chatbot-panel.is-open{display:flex}
    #impulsa-chatbot-header{padding:12px 14px;background:#0f172a;color:#fff;font-weight:600;display:flex;justify-content:space-between;align-items:center}
    #impulsa-chatbot-body{padding:14px;overflow:auto;font-size:14px;color:#334155;line-height:1.5}
    #impulsa-chatbot-options{display:flex;flex-direction:column;gap:8px;padding:0 14px 14px}
    .impulsa-chatbot-option{border:1px solid #cbd5e1;background:#f8fafc;border-radius:10px;padding:10px;text-align:left;cursor:pointer;font-size:13px}
    .impulsa-chatbot-option:hover{background:#e2e8f0}
  `;
  document.head.appendChild(style);
}

function mountWidget(http, config) {
  ensureStyles();

  const root = document.createElement('div');
  root.id = 'impulsa-chatbot-root';

  const panel = document.createElement('div');
  panel.id = 'impulsa-chatbot-panel';

  const header = document.createElement('div');
  header.id = 'impulsa-chatbot-header';
  header.innerHTML = `<span>${escapeHtml(config.name || 'Chat')}</span><button type="button" aria-label="Cerrar" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer">×</button>`;

  const body = document.createElement('div');
  body.id = 'impulsa-chatbot-body';

  const options = document.createElement('div');
  options.id = 'impulsa-chatbot-options';

  panel.appendChild(header);
  panel.appendChild(body);
  panel.appendChild(options);

  const bubble = document.createElement('button');
  bubble.id = 'impulsa-chatbot-bubble';
  bubble.type = 'button';
  bubble.setAttribute('aria-label', 'Abrir chat');
  bubble.textContent = '💬';

  root.appendChild(panel);
  root.appendChild(bubble);
  document.body.appendChild(root);

  const nodesById = Object.fromEntries((config.nodes || []).map((node) => [node.id, node]));
  const startNode = (config.nodes || []).find((node) => node.is_start) || config.nodes?.[0];

  function renderNode(node) {
    if (!node) return;
    body.textContent = node.body || config.initial_message || '';
    options.innerHTML = '';

    (node.options || []).forEach((option) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'impulsa-chatbot-option';
      btn.textContent = option.label;
      btn.addEventListener('click', () => handleOption(option, node));
      options.appendChild(btn);
    });

    trackEvent(http, 'question_viewed', { node_id: node.id });
  }

  function handleOption(option, node) {
    trackEvent(http, 'option_clicked', { node_id: node.id, option_id: option.id });

    if (option.action_type === 'whatsapp') {
      const phone = String(config.whatsapp || '').replace(/\D/g, '');
      if (phone) window.open(`https://wa.me/${phone}`, '_blank', 'noopener,noreferrer');
      trackEvent(http, 'whatsapp_clicked', { node_id: node.id, option_id: option.id });
      return;
    }

    if (option.action_type === 'restart') {
      renderNode(startNode);
      return;
    }

    if (option.action_type === 'close') {
      panel.classList.remove('is-open');
      trackEvent(http, 'chat_closed');
      return;
    }

    if (option.action_type === 'go_to_node' && option.target_node_id) {
      renderNode(nodesById[option.target_node_id]);
    }
  }

  bubble.addEventListener('click', () => {
    panel.classList.toggle('is-open');
    if (panel.classList.contains('is-open')) {
      trackEvent(http, 'bubble_opened');
      renderNode(startNode);
    }
  });

  header.querySelector('button')?.addEventListener('click', () => {
    panel.classList.remove('is-open');
    trackEvent(http, 'chat_closed');
  });
}

async function trackEvent(http, eventType, extra = {}) {
  try {
    await http.request('/chatbot/events', {
      method: 'POST',
      body: {
        event_type: eventType,
        page_url: `${window.location.pathname}${window.location.search}`,
        ...extra,
      },
      keepalive: true,
    });
  } catch {
    // ignore tracking errors
  }
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}
