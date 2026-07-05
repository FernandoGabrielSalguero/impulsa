<?php

namespace App\Enums;

enum WebsiteSubscriptionStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Cancelled = 'cancelled';
}
