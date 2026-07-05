<?php

return [

    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),

    'webhook_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),

    'subscription_plan_url' => rtrim((string) env('MERCADOPAGO_SUBSCRIPTION_PLAN_URL', ''), '/'),

];
