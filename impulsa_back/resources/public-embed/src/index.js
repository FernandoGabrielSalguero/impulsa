import { readConfig, isFeatureEnabled } from './core/config.js';
import { createHttp } from './core/http.js';
import { createLogger } from './core/logger.js';
import { initVisits } from './modules/visits.js';
import { initBlog } from './modules/blog.js';
import { initProducts } from './modules/products.js';
import { initSubscription } from './modules/subscription.js';
import { initChatbot } from './modules/chatbot.js';
import { initContact } from './modules/contact.js';

async function boot() {
  const { publicKey, apiBaseUrl, features } = readConfig();

  if (!publicKey || !apiBaseUrl) {
    console.warn('[Impulsa SDK] IMPULSA_API_CONFIG incompleto (publicKey, apiBaseUrl).');
    return;
  }

  const http = createHttp(publicKey, apiBaseUrl);
  let bootstrap = null;

  try {
    const response = await http.request('/bootstrap');
    bootstrap = response?.data || null;
  } catch (error) {
    console.warn('[Impulsa SDK] No se pudo cargar bootstrap:', error.message);
  }

  const integrationName = bootstrap?.integration?.project_name || '';
  const logger = createLogger(integrationName, publicKey);
  const bootstrapFeatures = bootstrap?.features || {};
  const ownerContact = bootstrap?.owner_contact || null;

  const Impulsa = {
    version: '1.0.0',
    config: { publicKey, apiBaseUrl },
    bootstrap,
    blog: null,
    products: null,
    contact: null,
    chatbot: null,
    visits: null,
    subscription: null,
  };

  window.Impulsa = Impulsa;

  if (isFeatureEnabled(features, 'subscription', bootstrapFeatures)) {
    Impulsa.subscription = await initSubscription(http, logger, ownerContact);
  } else {
    logger.set('subscription', 'skipped', '—', 'disabled in config');
  }

  if (isFeatureEnabled(features, 'visits', bootstrapFeatures)) {
    Impulsa.visits = await initVisits(http, logger);
  } else {
    logger.set('visits', 'skipped', '—', 'disabled in config');
  }

  if (isFeatureEnabled(features, 'blog', bootstrapFeatures)) {
    Impulsa.blog = await initBlog(http, logger);
  } else {
    logger.set('blog', 'skipped', '—', 'disabled in config');
  }

  if (isFeatureEnabled(features, 'products', bootstrapFeatures)) {
    Impulsa.products = await initProducts(http, logger);
  } else {
    logger.set('products', 'skipped', '—', 'disabled in config');
  }

  if (isFeatureEnabled(features, 'chatbot', bootstrapFeatures)) {
    Impulsa.chatbot = await initChatbot(http, logger);
  } else {
    logger.set('chatbot', 'skipped', '—', 'disabled in config');
  }

  if (isFeatureEnabled(features, 'contact', bootstrapFeatures)) {
    Impulsa.contact = initContact(http, logger);
  } else {
    logger.set('contact', 'skipped', '—', 'disabled in config');
  }

  logger.printTable();
  window.dispatchEvent(new CustomEvent('impulsa:ready', { detail: Impulsa }));
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
