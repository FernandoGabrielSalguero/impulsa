export function createHttp(publicKey, apiBaseUrl) {
  const base = `${apiBaseUrl}/v1/public`;

  async function request(path, options = {}) {
    const method = options.method || 'GET';
    const url = `${base}${path}${path.includes('?') ? '&' : '?'}public_key=${encodeURIComponent(publicKey)}`;
    const headers = {
      Accept: 'application/json',
      'X-Impulsa-Public-Key': publicKey,
      ...(options.headers || {}),
    };

    const init = {
      method,
      headers,
      credentials: 'omit',
      keepalive: Boolean(options.keepalive),
    };

    if (options.body !== undefined) {
      headers['Content-Type'] = 'application/json';
      init.body = JSON.stringify(options.body);
    }

    const response = await fetch(url, init);
    let payload = null;

    try {
      payload = await response.json();
    } catch {
      payload = null;
    }

    if (!response.ok) {
      const message = payload?.message || `HTTP ${response.status}`;
      const error = new Error(message);
      error.status = response.status;
      error.payload = payload;
      throw error;
    }

    return payload;
  }

  return { request, base };
}
