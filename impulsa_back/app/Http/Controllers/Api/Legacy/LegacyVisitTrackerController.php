<?php

namespace App\Http\Controllers\Api\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LegacyVisitTrackerController extends Controller
{
    public function script(): Response
    {
        $script = <<<'JS'
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
JS;

        return response($script, 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
