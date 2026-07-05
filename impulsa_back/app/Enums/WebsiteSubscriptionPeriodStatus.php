<?php

namespace App\Enums;

enum WebsiteSubscriptionPeriodStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Grace = 'grace';
    case Waived = 'waived';
    case Overdue = 'overdue';
}
