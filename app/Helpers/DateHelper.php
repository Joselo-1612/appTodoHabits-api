<?php

namespace App\Helpers;

use DateTime;

class DateHelper
{

    public static function formatDate($date)
    {
        $dateFormat = new DateTime($date);
        $daySpecific = $dateFormat->format('l');

        return self::translateDay($daySpecific);
    }

    public static function translateDay($day)
    {
        $map = [
            'Monday' => 'lunes',
            'Tuesday' => 'martes',
            'Wednesday' => 'miércoles',
            'Thursday' => 'jueves',
            'Friday' => 'viernes',
            'Saturday' => 'sábado',
            'Sunday' => 'domingo',
        ];

        return $map[$day] ?? $day;
    }

    public static function getWeekCurrent(): array
    {
        $week = [];

        $today = new DateTime();
        $dayWeek = (int) $today->format('w'); // 0 = domingo, 6 = sábado

        // Clonar fecha actual
        $startWeek = clone $today;

        // Ir al domingo de la semana actual
        $startWeek->modify("-{$dayWeek} days");

        for ($i = 0; $i < 7; $i++) {
            $date = clone $startWeek;
            $date->modify("+{$i} days");
            $week[] = $date->format('Y-m-d');
        }

        return $week;
    }

    public static function getMonthsInRange(string $startDate, string $endDate): array {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        $start->modify('first day of this month');
        $end->modify('first day of next month');

        $months = [];

        while ($start < $end) {
            $months[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        return $months;
    }

}
