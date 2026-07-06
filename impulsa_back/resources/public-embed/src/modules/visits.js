export async function initVisits(http, logger) {
  const page = `${window.location.pathname || '/'}${window.location.search || ''}`;

  try {
    await http.request('/page-visit', {
      method: 'POST',
      body: { page },
      keepalive: true,
    });
    logger.set('visits', 'ok', 1, `page=${page}`);
  } catch (error) {
    logger.set('visits', 'error', '—', error.message);
  }

  return {
    trackContentView(contentType, contentId, pageUrl) {
      return http.request('/content-view', {
        method: 'POST',
        body: {
          content_type: contentType,
          content_id: contentId,
          page_url: pageUrl || page,
        },
        keepalive: true,
      });
    },
  };
}
