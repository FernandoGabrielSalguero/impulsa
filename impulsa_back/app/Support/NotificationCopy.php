<?php

namespace App\Support;

/**
 * Textos editables de notificaciones in-app.
 * Cambiá title/body acá; se guardan en user_notifications al emitir.
 */
class NotificationCopy
{
    /** @return array{title: string, body: string} */
    public static function projectCommentCreated(string $projectName, string $deliverableTitle, string $actorLabel): array
    {
        return [
            'title' => 'Nuevo comentario en un objetivo',
            'body' => $actorLabel.' comentó en "'.$deliverableTitle.'" del proyecto '.$projectName.'.',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectCreated(string $projectName, string $actorLabel): array
    {
        return [
            'title' => 'Nuevo proyecto creado',
            'body' => $actorLabel.' creó el proyecto '.$projectName.'.',
        ];
    }

    /**
     * @param  list<string>  $changeLines
     * @return array{title: string, body: string}
     */
    public static function projectUpdated(string $projectName, string $actorLabel, array $changeLines = []): array
    {
        return [
            'title' => 'Proyecto actualizado',
            'body' => self::withChanges(
                $actorLabel.' actualizó el proyecto '.$projectName.'.',
                $changeLines,
            ),
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectPhaseCreated(string $projectName, string $phaseTitle): array
    {
        return [
            'title' => 'Nueva fase en el proyecto',
            'body' => 'Se agregó la fase "'.$phaseTitle.'" en '.$projectName.'.',
        ];
    }

    /**
     * @param  list<string>  $changeLines
     * @return array{title: string, body: string}
     */
    public static function projectPhaseUpdated(string $projectName, string $phaseTitle, string $actorLabel, array $changeLines = []): array
    {
        return [
            'title' => 'Fase actualizada',
            'body' => self::withChanges(
                $actorLabel.' actualizó la fase "'.$phaseTitle.'" en '.$projectName.'.',
                $changeLines,
            ),
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectPhaseDeleted(string $projectName, string $phaseTitle, string $actorLabel): array
    {
        return [
            'title' => 'Fase eliminada',
            'body' => $actorLabel.' eliminó la fase "'.$phaseTitle.'" del proyecto '.$projectName.'.',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectDeliverableCreated(string $projectName, string $deliverableTitle): array
    {
        return [
            'title' => 'Nuevo objetivo en el proyecto',
            'body' => 'Se agregó el objetivo "'.$deliverableTitle.'" en '.$projectName.'.',
        ];
    }

    /**
     * @param  list<string>  $changeLines
     * @return array{title: string, body: string}
     */
    public static function projectDeliverableUpdated(
        string $projectName,
        string $deliverableTitle,
        string $actorLabel,
        array $changeLines = [],
    ): array {
        return [
            'title' => 'Objetivo actualizado',
            'body' => self::withChanges(
                $actorLabel.' actualizó el objetivo "'.$deliverableTitle.'" en '.$projectName.'.',
                $changeLines,
            ),
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectDeliverableDeleted(string $projectName, string $deliverableTitle, string $actorLabel): array
    {
        return [
            'title' => 'Objetivo eliminado',
            'body' => $actorLabel.' eliminó el objetivo "'.$deliverableTitle.'" del proyecto '.$projectName.'.',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectStatusChanged(
        string $projectName,
        string $entityLabel,
        string $statusLabel,
        string $actorLabel,
    ): array {
        return [
            'title' => 'Estado actualizado',
            'body' => $actorLabel.' cambió el estado de '.$entityLabel.' a "'.$statusLabel.'" en '.$projectName.'.',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function projectUpdatedForClient(string $projectName, string $updateTitle): array
    {
        return [
            'title' => 'Actualización de tu proyecto',
            'body' => $updateTitle.' en '.$projectName.'.',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function goalObjectiveCompleted(string $goalTitle, string $objectiveTitle, int $progressPercent, int $remaining): array
    {
        $body = 'Completaste "'.$objectiveTitle.'" en la meta '.$goalTitle.'. Avance: '.$progressPercent.'%.';

        if ($remaining > 0) {
            $body .= ' Quedan '.$remaining.' objetivo(s) por cumplir.';
        }

        return [
            'title' => 'Objetivo completado',
            'body' => $body,
        ];
    }

    /** @return array{title: string, body: string} */
    public static function goalCompleted(string $goalTitle): array
    {
        return [
            'title' => 'Meta completada',
            'body' => 'Felicitaciones, completaste todos los objetivos de la meta "'.$goalTitle.'".',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function goalReminderUpcoming(string $entityLabel, string $goalTitle): array
    {
        return [
            'title' => 'Vencimiento próximo',
            'body' => $entityLabel.' de la meta "'.$goalTitle.'" vence mañana.',
        ];
    }

    /** @return array{title: string, body: string} */
    public static function goalReminderOverdue(string $entityLabel, string $goalTitle): array
    {
        return [
            'title' => 'Vencimiento superado',
            'body' => $entityLabel.' de la meta "'.$goalTitle.'" está vencido.',
        ];
    }

    /**
     * @param  list<string>  $changeLines
     */
    private static function withChanges(string $intro, array $changeLines): string
    {
        $lines = array_values(array_filter(array_map(
            static fn ($line): string => trim((string) $line),
            $changeLines,
        )));

        if ($lines === []) {
            return $intro;
        }

        return $intro.' '.implode(' ', $lines);
    }
}
