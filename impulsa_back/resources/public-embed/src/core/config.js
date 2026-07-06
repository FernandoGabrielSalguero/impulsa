export function readConfig() {
  const raw = window.IMPULSA_API_CONFIG || {};
  const publicKey = String(raw.publicKey || raw.public_key || '').trim();
  const apiBaseUrl = String(raw.apiBaseUrl || raw.api_base_url || '').replace(/\/+$/, '');
  const features = raw.features || null;

  return { publicKey, apiBaseUrl, features, raw };
}

export function publicBaseUrl(apiBaseUrl) {
  return `${apiBaseUrl}/v1/public`;
}

export function isFeatureEnabled(features, key, bootstrapFeatures) {
  if (features && Object.prototype.hasOwnProperty.call(features, key)) {
    return Boolean(features[key]);
  }

  const bootstrap = bootstrapFeatures?.[key];
  return bootstrap ? Boolean(bootstrap.enabled) : true;
}
