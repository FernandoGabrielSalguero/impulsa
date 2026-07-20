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
    public static function projectPhaseCreated(string $projectName, string $phaseTitle): array
    {
        return [
            'title' => 'Nueva fase en el proyecto',
            'body' => 'Se agregó la fase "'.$phaseTitle.'" en '.$projectName.'.',
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

    /** @return array{title: string, body: string} */
    public static function projectUpdatedForClient(string $projectName, string $updateTitle): array
    {
        return [
            'title' => 'Actualización de tu proyecto',
            'body' => $updateTitle.' en '.$projectName.'.',
        ];
    }
}
