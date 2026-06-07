(() => {
  const config = window.IMPULSA_API_CONFIG || {};
  const publicKey = typeof config.publicKey === "string" ? config.publicKey.trim() : "";
  const apiBaseUrl = typeof config.apiBaseUrl === "string" ? config.apiBaseUrl.replace(/\/+$/, "") : "";

  if (!publicKey || !apiBaseUrl) {
    return;
  }

  const endpoint = `${apiBaseUrl}/visit_user_page/index.php`;
  const page = `${window.location.pathname || "/"}${window.location.search || ""}`;

  window.fetch(endpoint, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      public_key: publicKey,
      page
    }),
    keepalive: true
  }).catch(() => {});
})();
