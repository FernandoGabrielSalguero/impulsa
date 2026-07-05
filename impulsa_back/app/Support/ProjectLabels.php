<?php

namespace App\Support;

final class ProjectLabels
{
    /** @var array<string, string> */
    private const STATUS = [
        'draft' => 'Borrador',
        'planned' => 'Planificado',
        'in_progress' => 'En progreso',
        'paused' => 'Pausado',
        'in_review' => 'En revisión',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ];

    /** @var array<string, string> */
    private const PRIORITY = [
        'low' => 'Baja',
        'medium' => 'Media',
        'high' => 'Alta',
        'urgent' => 'Urgente',
    ];

    /** @var array<string, string> */
    private const PROJECT_TYPE = [
        'software' => 'Software',
        'landing_page' => 'Landing page',
        'website' => 'Sitio web',
        'manual' => 'Manual',
    ];

    /** @var array<string, string> */
    private const PHASE_STATUS = [
        'pending' => 'Pendiente',
        'in_progress' => 'En progreso',
        'blocked' => 'Bloqueada',
        'done' => 'Finalizada',
    ];

    /** @var array<string, string> */
    private const DELIVERABLE_TYPE = [
        'document' => 'Documento',
        'design' => 'Diseño',
        'development' => 'Desarrollo',
        'deployment' => 'Publicación',
        'training' => 'Capacitación',
        'other' => 'Otro',
    ];

    /** @var array<string, string> */
    private const DELIVERABLE_STATUS = [
        'pending' => 'Pendiente',
        'in_progress' => 'En progreso',
        'ready_for_review' => 'Listo para revisión',
        'delivered' => 'Entregado',
    ];

    public static function statusLabel(?string $status): string
    {
        return self::STATUS[$status ?? ''] ?? ($status ?? '—');
    }

    public static function priorityLabel(?string $priority): string
    {
        return self::PRIORITY[$priority ?? ''] ?? ($priority ?? '—');
    }

    public static function projectTypeLabel(?string $type): string
    {
        return self::PROJECT_TYPE[$type ?? ''] ?? ($type ?? '—');
    }

    public static function phaseStatusLabel(?string $status): string
    {
        return self::PHASE_STATUS[$status ?? ''] ?? ($status ?? '—');
    }

    public static function deliverableTypeLabel(?string $type): string
    {
        return self::DELIVERABLE_TYPE[$type ?? ''] ?? ($type ?? '—');
    }

    public static function deliverableStatusLabel(?string $status): string
    {
        return self::DELIVERABLE_STATUS[$status ?? ''] ?? ($status ?? '—');
    }

    /** @return list<string> */
    public static function statuses(): array
    {
        return array_keys(self::STATUS);
    }

    /** @return list<string> */
    public static function priorities(): array
    {
        return array_keys(self::PRIORITY);
    }

    /** @return list<string> */
    public static function phaseStatuses(): array
    {
        return array_keys(self::PHASE_STATUS);
    }

    /** @return list<string> */
    public static function deliverableTypes(): array
    {
        return array_keys(self::DELIVERABLE_TYPE);
    }

    /** @return list<string> */
    public static function deliverableStatuses(): array
    {
        return array_keys(self::DELIVERABLE_STATUS);
    }
}
