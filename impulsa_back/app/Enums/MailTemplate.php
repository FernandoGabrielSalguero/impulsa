<?php

namespace App\Enums;

enum MailTemplate: string
{
    case VerifyEmail = 'verificacion_correo';
    case NewUserCliente = 'new_user_cliente';
    case SolicitudPaginaWeb = 'solicitud_pagina_web';
    case RequestPageExternal = 'request_page_external';
    case NewProject = 'new_project';
    case SolicitudMarketing = 'solicitud_marketing';
    case NotificacionContactoWebPublica = 'notificacion_contacto_web_publica';
    case ReenvioCorreoLog = 'reenvio_correo_log';
    case SubscriptionMonthlyNotice = 'subscription_monthly_notice';
    case SubscriptionPaymentReminder = 'subscription_payment_reminder';
}