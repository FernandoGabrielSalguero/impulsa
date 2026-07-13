<?php

namespace App\Support;

use App\Enums\MailTemplate;

class MailTemplateLabels
{
    public static function labelFor(?string $template): string
    {
        if ($template === null || $template === '') {
            return 'Sin template';
        }

        $enum = MailTemplate::tryFrom($template);

        if ($enum !== null) {
            return match ($enum) {
                MailTemplate::VerifyEmail => 'Verificación de correo',
                MailTemplate::ResetPassword => 'Restablecer contraseña',
                MailTemplate::NewUserCliente => 'Alta de usuario cliente',
                MailTemplate::SolicitudPaginaWeb => 'Solicitud página web',
                MailTemplate::RequestPageExternal => 'Solicitud página externa',
                MailTemplate::NewProject => 'Nuevo proyecto',
                MailTemplate::ProjectProgressUpdate => 'Avance de proyecto',
                MailTemplate::SolicitudMarketing => 'Solicitud marketing',
                MailTemplate::NotificacionContactoWebPublica => 'Contacto web pública',
                MailTemplate::ReenvioCorreoLog => 'Reenvío de correo',
                MailTemplate::SubscriptionMonthlyNotice => 'Aviso mensual de suscripción',
                MailTemplate::SubscriptionPaymentReminder => 'Recordatorio de pago de suscripción',
            };
        }

        return match ($template) {
            default => $template,
        };
    }
}
