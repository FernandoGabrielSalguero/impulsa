(function () {
  var currentScript = document.currentScript;
  if (!currentScript) {
    var scripts = document.getElementsByTagName('script');
    currentScript = scripts[scripts.length - 1];
  }

  if (!currentScript || !currentScript.src) {
    return;
  }

  var scriptUrl = new URL(currentScript.src, window.location.href);
  var publicKey = scriptUrl.searchParams.get('key') || scriptUrl.searchParams.get('public_key') || '';
  if (!publicKey) {
    return;
  }

  var apiBase = scriptUrl.origin + '/api';
  var state = {
    chatbot: null,
    nodesById: {},
    panelOpen: false,
    started: false
  };

  var classes = {
    root: 'impulsa-chatbot-root',
    bubble: 'impulsa-chatbot-bubble',
    panel: 'impulsa-chatbot-panel',
    hidden: 'impulsa-chatbot-hidden',
    header: 'impulsa-chatbot-header',
    body: 'impulsa-chatbot-body',
    actions: 'impulsa-chatbot-actions',
    option: 'impulsa-chatbot-option',
    close: 'impulsa-chatbot-close',
    avatar: 'impulsa-chatbot-avatar',
    title: 'impulsa-chatbot-title',
    message: 'impulsa-chatbot-message',
    footer: 'impulsa-chatbot-footer'
  };

  function postEvent(payload) {
    try {
      fetch(apiBase + '/chatbot_event/index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(Object.assign({
          public_key: publicKey,
          chatbot_id: state.chatbot.id,
          page_url: window.location.href
        }, payload)),
        keepalive: true
      }).catch(function () {});
    } catch (error) {}
  }

  function normalizeWhatsapp(raw) {
    return String(raw || '').replace(/[^\d]/g, '');
  }

  function resolveAvatarUrl(raw) {
    var value = String(raw || '').trim();
    if (!value) {
      return '';
    }

    if (/^https?:\/\//i.test(value)) {
      return value;
    }

    if (value.charAt(0) === '/') {
      if (value.indexOf('/impulsa_emprende/assets/images/avatar_bot/') === 0) {
        return scriptUrl.origin + '/assets/images/avatar_bot/' + value.split('/').pop();
      }
      return scriptUrl.origin + value;
    }

    return scriptUrl.origin + '/assets/images/avatar_bot/' + value.replace(/^\/+/, '');
  }

  function ensureShell() {
    if (document.getElementById(classes.root)) {
      return document.getElementById(classes.root);
    }

    var root = document.createElement('div');
    root.id = classes.root;
    root.innerHTML = ''
      + '<style>'
      + '#' + classes.root + '{position:fixed;right:20px;bottom:20px;z-index:2147483000;font-family:Inter,Arial,sans-serif}'
      + '.' + classes.hidden + '{display:none!important}'
      + '.' + classes.bubble + '{width:60px;height:60px;border:none;border-radius:999px;background:#112c4e;color:#fff;box-shadow:0 14px 30px rgba(17,44,78,.28);cursor:pointer;font-size:28px}'
      + '.' + classes.panel + '{position:absolute;right:0;bottom:76px;width:min(360px,calc(100vw - 24px));background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 20px 50px rgba(15,23,42,.24);border:1px solid rgba(17,44,78,.12)}'
      + '.' + classes.header + '{display:flex;align-items:center;gap:12px;padding:16px 18px;background:linear-gradient(135deg,#112c4e,#2eb3ba);color:#fff}'
      + '.' + classes.avatar + '{width:42px;height:42px;border-radius:999px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;overflow:hidden;font-weight:800}'
      + '.' + classes.avatar + ' img{width:100%;height:100%;object-fit:cover}'
      + '.' + classes.title + '{display:block;font-size:15px;font-weight:700}'
      + '.' + classes.close + '{margin-left:auto;background:transparent;border:none;color:#fff;font-size:20px;cursor:pointer}'
      + '.' + classes.body + '{padding:16px 18px;display:grid;gap:14px;max-height:60vh;overflow:auto;background:#f7fafc}'
      + '.' + classes.message + '{padding:12px 14px;border-radius:16px;background:#fff;color:#0f172a;border:1px solid rgba(17,44,78,.08);line-height:1.4}'
      + '.' + classes.actions + '{display:grid;gap:10px}'
      + '.' + classes.option + '{width:100%;text-align:left;border:none;border-radius:14px;padding:12px 14px;background:#dff5f3;color:#0f172a;cursor:pointer;font-weight:600}'
      + '.' + classes.option + ':hover{background:#c5ece7}'
      + '.' + classes.footer + '{padding:12px 18px;background:#fff;border-top:1px solid rgba(17,44,78,.08);font-size:12px;color:#475569}'
      + '@media (max-width:640px){#' + classes.root + '{right:12px;left:12px;bottom:12px}.' + classes.panel + '{width:100%;right:0}}'
      + '</style>'
      + '<button class="' + classes.bubble + '" type="button" aria-label="Abrir chatbot">💬</button>'
      + '<section class="' + classes.panel + ' ' + classes.hidden + '" aria-hidden="true">'
      + '  <div class="' + classes.header + '">'
      + '    <div class="' + classes.avatar + '"></div>'
      + '    <div><strong class="' + classes.title + '"></strong><div>Respuestas rapidas</div></div>'
      + '    <button class="' + classes.close + '" type="button" aria-label="Cerrar">×</button>'
      + '  </div>'
      + '  <div class="' + classes.body + '"></div>'
      + '  <div class="' + classes.footer + '">Chatbot de impulsa</div>'
      + '</section>';

    document.body.appendChild(root);
    return root;
  }

  function openPanel(root) {
    var panel = root.querySelector('.' + classes.panel);
    panel.classList.remove(classes.hidden);
    panel.setAttribute('aria-hidden', 'false');
    if (!state.panelOpen) {
      state.panelOpen = true;
      postEvent({ event_type: 'bubble_opened' });
    }
    if (!state.started) {
      state.started = true;
      renderNode(state.chatbot.start_node_id);
    }
  }

  function closePanel(root) {
    var panel = root.querySelector('.' + classes.panel);
    panel.classList.add(classes.hidden);
    panel.setAttribute('aria-hidden', 'true');
    if (state.panelOpen) {
      state.panelOpen = false;
      postEvent({ event_type: 'chat_closed' });
    }
  }

  function renderNode(nodeId) {
    var root = document.getElementById(classes.root);
    if (!root) {
      return;
    }

    var node = state.nodesById[String(nodeId)];
    if (!node) {
      return;
    }

    var body = root.querySelector('.' + classes.body);
    var html = ''
      + '<div class="' + classes.message + '">' + escapeHtml(state.chatbot.initial_message || '') + '</div>'
      + '<div class="' + classes.message + '"><strong>' + escapeHtml(node.title || '') + '</strong><br>' + escapeHtml(node.body || '') + '</div>'
      + '<div class="' + classes.actions + '">';

    node.options.forEach(function (option, index) {
      html += '<button class="' + classes.option + '" type="button" data-option-index="' + index + '">' + escapeHtml(option.label || 'Continuar') + '</button>';
    });

    html += '</div>';
    body.innerHTML = html;

    body.querySelectorAll('[data-option-index]').forEach(function (button) {
      button.addEventListener('click', function () {
        var option = node.options[Number(button.getAttribute('data-option-index'))];
        if (!option) {
          return;
        }

        postEvent({
          event_type: option.action_type === 'whatsapp' ? 'whatsapp_clicked' : 'option_clicked',
          node_id: node.id,
          option_id: option.id
        });

        if (option.action_type === 'go_to_node' && option.target_node_id) {
          renderNode(option.target_node_id);
          return;
        }

        if (option.action_type === 'restart') {
          renderNode(state.chatbot.start_node_id);
          return;
        }

        if (option.action_type === 'close') {
          closePanel(root);
          return;
        }

        if (option.action_type === 'whatsapp') {
          var whatsapp = normalizeWhatsapp(state.chatbot.whatsapp);
          if (whatsapp) {
            window.open('https://wa.me/' + whatsapp, '_blank', 'noopener');
          }
        }
      });
    });

    postEvent({ event_type: 'question_viewed', node_id: node.id });
  }

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function boot(chatbot) {
    state.chatbot = chatbot;
    state.nodesById = {};
    chatbot.nodes.forEach(function (node) {
      state.nodesById[String(node.id)] = node;
    });

    var root = ensureShell();
    var avatar = root.querySelector('.' + classes.avatar);
    var title = root.querySelector('.' + classes.title);
    title.textContent = chatbot.name || 'Chatbot';

    var avatarUrl = resolveAvatarUrl(chatbot.avatar_url);
    if (avatarUrl) {
      avatar.innerHTML = '<img src="' + escapeHtml(avatarUrl) + '" alt="">';
    } else {
      avatar.textContent = (chatbot.name || 'C').charAt(0).toUpperCase();
    }

    root.querySelector('.' + classes.bubble).addEventListener('click', function () {
      openPanel(root);
    });
    root.querySelector('.' + classes.close).addEventListener('click', function () {
      closePanel(root);
    });

    postEvent({ event_type: 'widget_loaded' });
  }

  fetch(apiBase + '/chatbot_config/index.php?public_key=' + encodeURIComponent(publicKey), {
    method: 'GET',
    credentials: 'omit'
  })
    .then(function (response) { return response.json(); })
    .then(function (payload) {
      if (!payload || payload.success !== true || payload.has_chatbot !== true || !payload.chatbot) {
        return;
      }
      boot(payload.chatbot);
    })
    .catch(function () {});
})();
