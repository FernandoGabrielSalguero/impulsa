<?php

namespace App\Support;

use Carbon\Carbon;

class BusinessDayHelper
{
    public static function isBusinessDay(Carbon $date): bool
    {
        if ($date->isWeekend()) {
            return false;
        }

        $holidays = config('impulsa.business_day_holidays', []);

        return ! in_array($date->toDateString(), $holidays, true);
    }

    public static function isFirstBusinessDayOfMonth(Carbon $date): bool
    {
        if (! self::isBusinessDay($date)) {
            return false;
        }

        $cursor = $date->copy()->startOfMonth();

        while (! self::isBusinessDay($cursor)) {
            $cursor->addDay();
        }

        return $cursor->isSameDay($date);
    }
}
