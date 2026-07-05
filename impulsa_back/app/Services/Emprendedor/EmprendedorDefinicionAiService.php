<?php

namespace App\Services\Emprendedor;

use App\Models\UserAuth;
use App\Services\Ai\AiUsageContext;
use App\Services\Ai\DeepSeekClient;
use App\Support\EmprendedorDefinicionStructureTemplates;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EmprendedorDefinicionAiService
{
    public function __construct(
        private readonly DeepSeekClient $deepSeekClient,
    ) {}

    /** @param array<string, mixed> $fields */
    public function generate(
        UserAuth $user,
        string $type,
        array $fields,
        bool $preferAi = true,
        ?string $ipAddress = null,
    ): array {
        $template = $this->templateForType($type, $fields);

        if ($preferAi) {
            if (! $this->deepSeekClient->isConfigured()) {
                throw ValidationException::withMessages([
                    'ai' => ['DeepSeek no está configurado. Agregá DEEPSEEK_API_KEY en impulsa_back/.env y reiniciá el servidor.'],
                ]);
            }

            try {
                return [
                    'suggestion' => $this->generateWithAi($user, $type, $fields, $ipAddress),
                    'source' => 'ai',
                ];
            } catch (\Throwable $exception) {
                Log::warning('DeepSeek definicion generate failed', [
                    'type' => $type,
                    'user_id' => $user->id,
                    'message' => $exception->getMessage(),
                ]);

                throw ValidationException::withMessages([
                    'ai' => ['No pudimos generar con IA: ' . $exception->getMessage()],
                ]);
            }
        }

        if ($template === '') {
            throw ValidationException::withMessages([
                'fields' => ['Completá al menos un campo para generar la frase.'],
            ]);
        }

        return [
            'suggestion' => $template,
            'source' => 'template',
        ];
    }

    /** @param array<string, mixed> $fields */
    private function templateForType(string $type, array $fields): string
    {
        return match ($type) {
            'mision' => EmprendedorDefinicionStructureTemplates::mision($fields),
            'vision' => EmprendedorDefinicionStructureTemplates::vision($fields),
            'buyer_persona' => EmprendedorDefinicionStructureTemplates::buyerPersona($fields),
            default => throw ValidationException::withMessages([
                'type' => ['Tipo de definición inválido.'],
            ]),
        };
    }

    /** @param array<string, mixed> $fields */
    private function generateWithAi(
        UserAuth $user,
        string $type,
        array $fields,
        ?string $ipAddress,
    ): string {
        $systemPrompt = (string) config('deepseek_definicion.system_prompt');

        $template = (string) config("deepseek_definicion.prompts.{$type}", '');

        if ($template === '') {
            throw ValidationException::withMessages([
                'type' => ['No hay prompt configurado para este módulo en config/deepseek_definicion.php.'],
            ]);
        }

        $userPrompt = $this->replacePromptPlaceholders($template, $fields);

        $context = new AiUsageContext(
            userAuthId: (int) $user->id,
            feature: "emprendedor.definicion.{$type}",
            metadata: [
                'type' => $type,
                'fields_count' => count(array_filter($fields, static fn ($v): bool => trim((string) $v) !== '')),
            ],
            ipAddress: $ipAddress,
        );

        return $this->deepSeekClient->chat($systemPrompt, $userPrompt, $context);
    }

    /** @param array<string, mixed> $fields */
    private function replacePromptPlaceholders(string $template, array $fields): string
    {
        $replacements = [];

        foreach ($fields as $key => $value) {
            $replacements['{' . $key . '}'] = trim((string) $value);
        }

        return strtr($template, $replacements);
    }
}
