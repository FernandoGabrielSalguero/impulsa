<?php

namespace App\Http\Resources;

use App\Support\ChatbotLabels;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminChatbotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $disabledByAdmin = (bool) $this->value('disabled_by_admin', false);
        $status = (string) ($this->value('status') ?? 'inactive');
        $integrationStatus = (string) ($this->value('integration_status') ?? 'inactive');

        return [
            'id' => (int) $this->value('id'),
            'api_integration_id' => (int) $this->value('api_integration_id'),
            'name' => (string) $this->value('name'),
            'project_name' => (string) $this->value('project_name'),
            'allowed_domain' => (string) $this->value('allowed_domain'),
            'integration_status' => $integrationStatus,
            'status' => $status,
            'status_label' => ChatbotLabels::statusLabel($status),
            'is_publicly_available' => $status === 'active'
                && ! $disabledByAdmin
                && $integrationStatus === 'active',
            'disabled_by_admin' => $disabledByAdmin,
            'admin_lock_label' => ChatbotLabels::adminLockLabel($disabledByAdmin),
            'metrics' => [
                'widget_loaded' => (int) $this->value('total_widget_loaded', 0),
                'bubble_opened' => (int) $this->value('total_bubble_opened', 0),
                'question_viewed' => (int) $this->value('total_question_viewed', 0),
                'option_clicked' => (int) $this->value('total_option_clicked', 0),
                'whatsapp_clicked' => (int) $this->value('total_whatsapp_clicked', 0),
            ],
            'created_at' => $this->formatDate($this->value('created_at')),
            'updated_at' => $this->formatDate($this->value('updated_at')),
            'last_activity' => $this->formatDate($this->value('last_activity')),
        ];
    }

    private function value(string $key, mixed $default = null): mixed
    {
        $resource = $this->resource;

        if (is_object($resource) && property_exists($resource, $key)) {
            return $resource->{$key} ?? $default;
        }

        if (is_array($resource) && array_key_exists($key, $resource)) {
            return $resource[$key];
        }

        return $default;
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        try {
            return Carbon::parse((string) $value)->toISOString();
        } catch (\Throwable) {
            return null;
        }
    }
}
