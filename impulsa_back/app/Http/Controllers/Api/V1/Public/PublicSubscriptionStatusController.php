<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\Models\WebsiteSubscription;
use App\Services\WebsiteSubscription\SubscriptionNotificationService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionAccessService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionPeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicSubscriptionStatusController extends Controller
{
    public function __construct(
        private readonly WebsiteSubscriptionAccessService $accessService,
        private readonly WebsiteSubscriptionPeriodService $periodService,
        private readonly SubscriptionNotificationService $notificationService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $subscription = WebsiteSubscription::query()
            ->with('mercadopagoPlan')
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($subscription === null) {
            return response()->json([
                'access_allowed' => true,
                'period' => now()->format('Y-m'),
                'status' => 'not_configured',
                'amount' => 0,
                'currency' => 'ARS',
                'payment_url' => config('mercadopago.subscription_plan_url'),
                'message' => 'Suscripción no configurada.',
            ]);
        }

        $this->periodService->ensureRollingPeriods($subscription);
        $period = $this->accessService->currentPeriod($subscription);

        if ($period !== null) {
            $period = $this->accessService->syncOverdueStatus($period);
            $this->notificationService->maybeSendFirstBusinessDayNotice($subscription, $period, $integration);
        }

        $result = $this->accessService->evaluateAccess($subscription, $integration, $period);

        return response()->json($result);
    }

    public function guardScript(): Response
    {
        $script = <<<'JS'
(function () {
  var config = window.IMPULSA_API_CONFIG || {};
  var publicKey = config.publicKey || config.public_key;
  var apiBaseUrl = (config.apiBaseUrl || config.api_base_url || '').replace(/\/$/, '');

  if (!publicKey || !apiBaseUrl) {
    console.warn('[Impulsa] IMPULSA_API_CONFIG incompleto.');
    return;
  }

  var endpoint = apiBaseUrl + '/api/v1/public/subscription-status?public_key=' + encodeURIComponent(publicKey);

  fetch(endpoint, { credentials: 'omit' })
    .then(function (response) { return response.json(); })
    .then(function (data) {
      if (data && data.access_allowed === false) {
        showBlockModal(data);
      }
    })
    .catch(function () {
      console.warn('[Impulsa] No se pudo verificar la suscripción.');
    });

  function showBlockModal(data) {
    if (document.getElementById('impulsa-subscription-block')) {
      return;
    }

    var overlay = document.createElement('div');
    overlay.id = 'impulsa-subscription-block';
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    overlay.style.cssText = [
      'position:fixed',
      'inset:0',
      'z-index:2147483647',
      'background:rgba(15,23,42,0.92)',
      'display:flex',
      'align-items:center',
      'justify-content:center',
      'padding:24px',
      'font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif'
    ].join(';');

    var card = document.createElement('div');
    card.style.cssText = [
      'max-width:520px',
      'width:100%',
      'background:#fff',
      'border-radius:16px',
      'padding:28px',
      'text-align:center',
      'box-shadow:0 20px 60px rgba(0,0,0,0.35)'
    ].join(';');

    var title = document.createElement('h2');
    title.textContent = 'Sitio temporalmente no disponible';
    title.style.cssText = 'margin:0 0 12px;font-size:22px;color:#0f172a';

    var message = document.createElement('p');
    message.textContent = data.message || 'Estamos experimentando inconvenientes técnicos.';
    message.style.cssText = 'margin:0 0 16px;color:#475569;line-height:1.5';

    card.appendChild(title);
    card.appendChild(message);

    if (data.payment_url) {
      var payLink = document.createElement('a');
      payLink.href = data.payment_url;
      payLink.target = '_blank';
      payLink.rel = 'noopener noreferrer';
      payLink.textContent = 'Regularizar suscripción en Mercado Pago';
      payLink.style.cssText = [
        'display:inline-block',
        'margin-top:8px',
        'padding:12px 18px',
        'background:#009ee3',
        'color:#fff',
        'text-decoration:none',
        'border-radius:10px',
        'font-weight:600'
      ].join(';');
      card.appendChild(payLink);
    }

    overlay.appendChild(card);
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
  }
})();
JS;

        return response($script, 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
