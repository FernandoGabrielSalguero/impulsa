<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DeepSeekClient
{
    public const PROVIDER = 'deepseek';

    public function __construct(
        private readonly AiUsageLogService $usageLogService,
    ) {}

    public function isConfigured(): bool
    {
        return trim((string) config('services.deepseek.key', '')) !== '';
    }

    public function chat(string $systemPrompt, string $userPrompt, AiUsageContext $context): string
    {
        $apiKey = trim((string) config('services.deepseek.key', ''));

        if ($apiKey === '') {
            throw new RuntimeException('DeepSeek no está configurado.');
        }

        $model = (string) config('services.deepseek.model', 'deepseek-chat');
        $baseUrl = rtrim((string) config('services.deepseek.base_url', 'https://api.deepseek.com'), '/');
        $url = $baseUrl . '/chat/completions';
        $startedAt = microtime(true);

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->timeout(30)
                ->post($url, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

            $latencyMs = (int) round((microtime(true) - $startedAt) * 1000);

            if (! $response->successful()) {
                $message = $this->buildHttpErrorMessage($response);
                $this->usageLogService->logFailure($context, self::PROVIDER, $model, $latencyMs, $message);

                throw new RuntimeException($message);
            }

            $content = trim((string) data_get($response->json(), 'choices.0.message.content', ''));

            if ($content === '') {
                $message = 'DeepSeek devolvió una respuesta vacía.';
                $this->usageLogService->logFailure($context, self::PROVIDER, $model, $latencyMs, $message);

                throw new RuntimeException($message);
            }

            $this->usageLogService->logSuccess(
                $context,
                self::PROVIDER,
                $model,
                $latencyMs,
                $this->extractTokenUsage($response),
            );

            return $content;
        } catch (RuntimeException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            $latencyMs = (int) round((microtime(true) - $startedAt) * 1000);
            $this->usageLogService->logFailure(
                $context,
                self::PROVIDER,
                $model,
                $latencyMs,
                $exception->getMessage(),
            );

            throw $exception;
        }
    }

    private function buildHttpErrorMessage(Response $response): string
    {
        $errorMessage = (string) data_get($response->json(), 'error.message', '');

        return $errorMessage !== ''
            ? "DeepSeek respondió con error (HTTP {$response->status()}): {$errorMessage}"
            : "DeepSeek respondió con error HTTP {$response->status()}.";
    }

    /** @return array{prompt_tokens: int|null, completion_tokens: int|null, total_tokens: int|null}|null */
    private function extractTokenUsage(Response $response): ?array
    {
        $usage = data_get($response->json(), 'usage');

        if (! is_array($usage)) {
            return null;
        }

        return [
            'prompt_tokens' => isset($usage['prompt_tokens']) ? (int) $usage['prompt_tokens'] : null,
            'completion_tokens' => isset($usage['completion_tokens']) ? (int) $usage['completion_tokens'] : null,
            'total_tokens' => isset($usage['total_tokens']) ? (int) $usage['total_tokens'] : null,
        ];
    }
}
