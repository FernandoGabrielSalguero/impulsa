<?php

namespace App\Services\Ai;

/**
 * Contexto reutilizable para registrar cualquier uso de IA.
 *
 * Convención de feature: "{modulo}.{accion}" o "{modulo}.{submodulo}.{accion}"
 * Ejemplos:
 * - emprendedor.definicion.mision
 * - emprendedor.definicion.vision
 * - admin.proyectos.resumen
 */
final class AiUsageContext
{
    /** @param array<string, mixed> $metadata */
    public function __construct(
        public readonly ?int $userAuthId,
        public readonly string $feature,
        public readonly array $metadata = [],
        public readonly ?string $ipAddress = null,
    ) {}

    /** @param array<string, mixed> $metadata */
    public function withMetadata(array $metadata): self
    {
        return new self(
            $this->userAuthId,
            $this->feature,
            array_merge($this->metadata, $metadata),
            $this->ipAddress,
        );
    }
}
