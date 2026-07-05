<?php

namespace App\Services\Admin;

use App\Models\MercadoPagoSubscriptionPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MercadoPagoSubscriptionPlanAdminService
{
    /** @return array{data: LengthAwarePaginator} */
    public function list(?string $q, ?string $status, int $perPage = 20): array
    {
        $query = MercadoPagoSubscriptionPlan::query()
            ->withCount('websiteSubscriptions')
            ->orderByDesc('updated_at')
            ->orderByDesc('id');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('name', 'like', $like)
                    ->orWhere('payment_url', 'like', $like)
                    ->orWhereRaw('CAST(id AS CHAR) LIKE ?', [$like]);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('status', $statusFilter);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
        ];
    }

    /** @return Collection<int, MercadoPagoSubscriptionPlan> */
    public function listActiveOptions(): Collection
    {
        return MercadoPagoSubscriptionPlan::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'amount', 'payment_url']);
    }

    public function find(int $planId): MercadoPagoSubscriptionPlan
    {
        $plan = MercadoPagoSubscriptionPlan::query()
            ->withCount('websiteSubscriptions')
            ->find($planId);

        if ($plan === null) {
            throw ValidationException::withMessages([
                'plan' => ['El plan no existe.'],
            ]);
        }

        return $plan;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): MercadoPagoSubscriptionPlan
    {
        return MercadoPagoSubscriptionPlan::query()->create([
            'name' => trim((string) $data['name']),
            'amount' => (float) $data['amount'],
            'payment_url' => $this->normalizeUrl((string) $data['payment_url']),
            'status' => $data['status'] ?? 'active',
            'notes' => isset($data['notes']) ? trim((string) $data['notes']) : null,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(MercadoPagoSubscriptionPlan $plan, array $data): MercadoPagoSubscriptionPlan
    {
        $plan->name = trim((string) $data['name']);
        $plan->amount = (float) $data['amount'];
        $plan->payment_url = $this->normalizeUrl((string) $data['payment_url']);
        $plan->status = $data['status'] ?? $plan->status;
        $plan->notes = isset($data['notes']) ? trim((string) $data['notes']) : null;
        $plan->save();

        return $this->find((int) $plan->id);
    }

    public function toggleStatus(MercadoPagoSubscriptionPlan $plan): MercadoPagoSubscriptionPlan
    {
        $plan->status = $plan->status === 'active' ? 'inactive' : 'active';
        $plan->save();

        return $this->find((int) $plan->id);
    }

    public function delete(MercadoPagoSubscriptionPlan $plan): void
    {
        if ($plan->websiteSubscriptions()->exists() || $plan->marketingPricingOptions()->exists()) {
            throw ValidationException::withMessages([
                'plan' => ['No se puede eliminar un plan asignado a suscripciones web o marketing. Desactivalo o reasigná los clientes.'],
            ]);
        }

        $plan->delete();
    }

    public function resolvePlanId(?int $planId): ?int
    {
        if ($planId === null) {
            return null;
        }

        $plan = MercadoPagoSubscriptionPlan::query()->find($planId);

        if ($plan === null) {
            throw ValidationException::withMessages([
                'mercadopago_subscription_plan_id' => ['El plan de Mercado Pago no existe.'],
            ]);
        }

        return (int) $plan->id;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            throw ValidationException::withMessages([
                'payment_url' => ['La URL de pago es obligatoria.'],
            ]);
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw ValidationException::withMessages([
                'payment_url' => ['La URL de pago no es válida.'],
            ]);
        }

        return $url;
    }
}
