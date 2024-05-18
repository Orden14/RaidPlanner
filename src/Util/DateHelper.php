<?php

namespace App\Util;

use DateTime;

final readonly class DateHelper
{
    /**
     * @return DateTime La semaine commence un dimanche à 00:00:00
     */
    public static function getStartOfWeek(): DateTime
    {
        $date = new DateTime();

        $currentDayOfWeek = (int)$date->format('w');

        if ($currentDayOfWeek === 0) {
            $date->setTime(0, 0);
        } else {
            $date->modify('Sunday last week midnight');
        }

        return $date;
    }

    /**
     * @return DateTime La semaine se termine un samedi à 23:59:59
     */
    public static function getEndOfWeek(): DateTime
    {
        $date = new DateTime();

        $currentDayOfWeek = (int)$date->format('w');

        if ($currentDayOfWeek === 6) {
            $date->setTime(23, 59, 59);
        } elseif ($currentDayOfWeek === 0) {
            $date->modify('Saturday next week 23:59:59');
        } else {
            $date->modify('Saturday this week 23:59:59');
        }

        return $date;
    }
}
