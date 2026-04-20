<?php

namespace App\Repositories;

use App\Enums\HabitEnum;
use App\Helpers\UtilHelper;
use App\Models\Habit;
use App\Models\HabitComplete;

class HabitRepository
{

    public function getDetailHabitComplete($habitId, $date)
    {
        $userId = UtilHelper::userSessionId();

        return HabitComplete::join('habits', 'habits.hab_id', '=', 'habit_completes.hac_hab_id')
                ->where('hac_hab_id', $habitId)
                ->where('hac_date', $date)
                ->where('habits.hab_use_id', $userId)
                ->first();
    }

    public function getListHabitComplete(array $weekDays)
    {
        $userId = UtilHelper::userSessionId();

        return Habit::join('habit_completes', 'habits.hab_id', 'habit_completes.hac_hab_id')
        ->where('habits.hab_use_id', $userId)
        ->where('habits.hab_status', HabitEnum::ACTIVE->value)
        ->whereIn('hac_date', $weekDays)
        ->select('hac_date', 'hac_id', 'hab_id')
        ->orderBy("hac_date", "asc")
        ->get();
    }

    public function getListHabitCompleteByPeriod($startDate, $endDate, $habitId)
    {
        $userId = UtilHelper::userSessionId();

        return Habit::join('habit_completes', 'habits.hab_id', 'habit_completes.hac_hab_id')
        ->where('habits.hab_use_id', $userId)
        ->where('habits.hab_id', $habitId)
        ->whereBetween('hac_date', [$startDate, $endDate])
        ->select('hac_date', 'hac_id', 'hab_id', 'hab_type_recurrence')
        ->get();
    }
}
