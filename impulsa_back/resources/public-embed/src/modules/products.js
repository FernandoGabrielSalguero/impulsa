export async function initProducts(http, logger) {
  try {
    const payload = await http.request('/products');
    const items = Array.isArray(payload?.data) ? payload.data : [];
    logger.set('products', items.length ? 'ok' : 'inactive', items.length, 'prefetch on init');
    return {
      list: () => Promise.resolve(items),
      get: (slug) => http.request(`/products/${encodeURIComponent(slug)}`).then((res) => res.data),
      items,
    };
  } catch (error) {
    logger.set('products', 'error', '—', error.message);
    return {
      list: () => Promise.resolve([]),
      get: () => Promise.reject(error),
      items: [],
    };
  }
}
