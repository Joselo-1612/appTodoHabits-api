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
            'Wednesday' => 'miercoles',
            'Thursday' => 'jueves',
            'Friday' => 'viernes',
            'Saturday' => 'sabado',
            'Sunday' => 'domingo',
        ];

        return $map[$day] ?? $day;
    }

    public static function getCurrentMonth($currentMonth = null): array
    {
        $days = [];

        $today = $currentMonth ? new DateTime($currentMonth) : new DateTime();

        // Primer día del mes
        $startMonth = new DateTime($today->format('Y-m-01'));

        // Último día del mes
        $endMonth = new DateTime($today->format('Y-m-t'));

        while ($startMonth <= $endMonth) {
            $days[] = $startMonth->format('Y-m-d');
            $startMonth->modify('+1 day');
        }

        return $days;
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

    public static function getCountDaysInWeek($year, $month, $dayWeek) {

        $start = new DateTime("$year-$month-01");
        $dayMonth = $start->format('t');

        $firstDayWeek = $start->format('N');

        // calcular el primer día buscado dentro del mes
        $firstDayFound = ($dayWeek >= $firstDayWeek)
            ? ($dayWeek - $firstDayWeek + 1)
            : (7 - $firstDayWeek + $dayWeek + 1);

        // total de ocurrencias
        return intval(floor(($dayMonth - $firstDayFound) / 7)) + 1;
    }

    public static function getConvertDateTiemeToTime($date){
        $date = new DateTime($date);

        return $date->format('H:i');
    }

    public static function getConvertDateTimeToDayText($date){
        $date = new DateTime($date);
        $dayInEnglish = $date->format('l');

        return self::translateDay($dayInEnglish);
    }

    public static function getConvertDateTimeToDayNumber($date){
        $date = new DateTime($date);
        return $date->format('d');
    }


    public static function getConvertDateTimeToDate($date){
        $date = new DateTime($date);
        return $date->format('Y-m-d');
    }
}
