export async function initBlog(http, logger) {
  try {
    const payload = await http.request('/blog');
    const items = Array.isArray(payload?.data) ? payload.data : [];
    logger.set('blog', items.length ? 'ok' : 'inactive', items.length, 'prefetch on init');
    return {
      list: () => Promise.resolve(items),
      get: (slug) => http.request(`/blog/${encodeURIComponent(slug)}`).then((res) => res.data),
      items,
    };
  } catch (error) {
    logger.set('blog', 'error', '—', error.message);
    return {
      list: () => Promise.resolve([]),
      get: () => Promise.reject(error),
      items: [],
    };
  }
}
