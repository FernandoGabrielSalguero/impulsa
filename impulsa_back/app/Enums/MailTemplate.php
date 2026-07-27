<?php

namespace App\Enums;

enum MailTemplate: string
{
    case VerifyEmail = 'verificacion_correo';
    case ResetPassword = 'reset_password';
    case NewUserCliente = 'new_user_cliente';
    case SolicitudPaginaWeb = 'solicitud_pagina_web';
    case RequestPageExternal = 'request_page_external';
    case NewProject = 'new_project';
    case ProjectProgressUpdate = 'project_progress_update';
    case SolicitudMarketing = 'solicitud_marketing';
    case NotificacionContactoWebPublica = 'notificacion_contacto_web_publica';
    case ReenvioCorreoLog = 'reenvio_correo_log';
    case SubscriptionMonthlyNotice = 'subscription_monthly_notice';
    case SubscriptionPaymentReminder = 'subscription_payment_reminder';
    case GoalObjectiveCompleted = 'goal_objective_completed';
    case GoalCompleted = 'goal_completed';
    case GoalReminder = 'goal_reminder';
}