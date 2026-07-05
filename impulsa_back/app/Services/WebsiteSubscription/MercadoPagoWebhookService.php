<?php

namespace App\Services\WebsiteSubscription;

use App\Models\UserAuth;
use App\Models\WebsiteSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoWebhookService
{
    public function __construct(
        private readonly WebsiteSubscriptionPeriodService $periodService,
    ) {}

    /** @param array<string, mixed> $payload */
    public function handle(array $payload): void
    {
        $this->configureSdk();

        $type = (string) ($payload['type'] ?? $payload['topic'] ?? '');
        $dataId = (string) ($payload['data']['id'] ?? $payload['id'] ?? '');

        if ($dataId === '') {
            return;
        }

        if (in_array($type, ['payment', 'merchant_order'], true) || str_contains($type, 'payment')) {
            $this->handlePaymentNotification($dataId);

            return;
        }

        if (str_contains($type, 'subscription') || str_contains($type, 'preapproval')) {
            Log::info('MercadoPago webhook recibido (suscripción)', [
                'type' => $type,
                'id' => $dataId,
            ]);
        }
    }

    private function handlePaymentNotification(string $paymentId): void
    {
        try {
            $client = new PaymentClient;
            $payment = $client->get((int) $paymentId);
        } catch (\Throwable $exception) {
            Log::warning('MercadoPago: no se pudo obtener pago', [
                'payment_id' => $paymentId,
                'error' => $exception->getMessage(),
            ]);

            return;
        }

        $status = (string) ($payment->status ?? '');

        if (! in_array($status, ['approved', 'authorized'], true)) {
            return;
        }

        $payerEmail = $this->extractPayerEmail($payment);

        if ($payerEmail === '') {
            return;
        }

        $owner = UserAuth::query()
            ->whereRaw('LOWER(correo) = ?', [$payerEmail])
            ->whereNotNull('email_verified_at')
            ->first();

        if ($owner === null) {
            Log::info('MercadoPago: pago sin usuario verificado coincidente', [
                'payment_id' => $paymentId,
                'email' => $payerEmail,
            ]);

            return;
        }

        $subscriptions = WebsiteSubscription::query()
            ->whereIn('api_integration_id', function ($query) use ($owner): void {
                $query->select('id')
                    ->from('api_integrations')
                    ->where('user_auth_id', $owner->id);
            })
            ->where('status', 'active')
            ->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $paidAt = Carbon::now();

        if (! empty($payment->date_approved)) {
            try {
                $paidAt = Carbon::parse($payment->date_approved);
            } catch (\Throwable) {
                // keep now
            }
        }

        foreach ($subscriptions as $subscription) {
            $period = $this->periodService->findOrCreatePeriod(
                $subscription,
                $paidAt->year,
                $paidAt->month,
            );

            $this->periodService->markPeriodPaid($period, (string) $paymentId, $paidAt);

            $preapprovalId = $this->extractPreapprovalId($payment);

            if ($preapprovalId !== null && empty($subscription->mercadopago_preapproval_id)) {
                $subscription->mercadopago_preapproval_id = $preapprovalId;
                $subscription->save();
            }
        }
    }

    private function extractPayerEmail(object $payment): string
    {
        $payer = $payment->payer ?? null;

        if (is_object($payer) && isset($payer->email)) {
            return strtolower(trim((string) $payer->email));
        }

        if (is_array($payer) && isset($payer['email'])) {
            return strtolower(trim((string) $payer['email']));
        }

        return '';
    }

    private function extractPreapprovalId(object $payment): ?string
    {
        $metadata = $payment->metadata ?? null;

        if (is_array($metadata) && ! empty($metadata['preapproval_id'])) {
            return (string) $metadata['preapproval_id'];
        }

        if (is_object($metadata) && isset($metadata->preapproval_id) && $metadata->preapproval_id !== '') {
            return (string) $metadata->preapproval_id;
        }

        return null;
    }

    private function configureSdk(): void
    {
        $token = (string) config('mercadopago.access_token');

        if ($token === '') {
            throw new \RuntimeException('MERCADOPAGO_ACCESS_TOKEN no configurado.');
        }

        MercadoPagoConfig::setAccessToken($token);
    }
}
