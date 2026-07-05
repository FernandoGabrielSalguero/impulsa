<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\DB;

class AiUsageLogService
{
    /** @param array<string, int|null>|null $tokenUsage */
    public function logSuccess(
        AiUsageContext $context,
        string $provider,
        string $model,
        int $latencyMs,
        ?array $tokenUsage = null,
    ): void {
        $this->insert($context, $provider, $model, 'success', $latencyMs, $tokenUsage, null);
    }

    public function logFailure(
        AiUsageContext $context,
        string $provider,
        string $model,
        int $latencyMs,
        string $errorMessage,
    ): void {
        $this->insert($context, $provider, $model, 'failed', $latencyMs, null, $errorMessage);
    }

    /** @param array<string, int|null>|null $tokenUsage */
    private function insert(
        AiUsageContext $context,
        string $provider,
        string $model,
        string $status,
        int $latencyMs,
        ?array $tokenUsage,
        ?string $errorMessage,
    ): void {
        if (! config('ai.usage_logging.enabled', true)) {
            return;
        }

        DB::table('ai_usage_logs')->insert([
            'user_auth_id' => $context->userAuthId,
            'provider' => $provider,
            'feature' => $context->feature,
            'model' => $model,
            'status' => $status,
            'prompt_tokens' => $tokenUsage['prompt_tokens'] ?? null,
            'completion_tokens' => $tokenUsage['completion_tokens'] ?? null,
            'total_tokens' => $tokenUsage['total_tokens'] ?? null,
            'latency_ms' => $latencyMs,
            'error_message' => $errorMessage !== null ? mb_substr($errorMessage, 0, 500) : null,
            'metadata_json' => $context->metadata !== []
                ? json_encode($context->metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : null,
            'ip_address' => $context->ipAddress,
            'created_at' => now(),
        ]);
    }
}
