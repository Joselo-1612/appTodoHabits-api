<?php

namespace App\Repositories;

use App\Enums\HabitEnum;
use App\Helpers\UtilHelper;
use App\Models\Habit;
use App\Models\HabitComplete;
use App\Models\HabitDay;
use DB;

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

    public function getListHabitDays($habitId)
    {
        return HabitDay::select(
            'had_id',
            'had_day',
            DB::raw("COALESCE(
                    NULLIF(habit_days.had_description, ''),
                    habits.hab_name) as had_description"),
            DB::raw("
                TIME_FORMAT(
                    COALESCE(
                        habit_days.had_schedule_ini,
                        habits.hab_schedule_ini
                    ),
                    '%H:%i'
                ) as had_schedule_ini
            "),
            DB::raw("
                TIME_FORMAT(
                    COALESCE(
                        habit_days.had_schedule_end,
                        habits.hab_schedule_end
                    ),
                    '%H:%i'
                ) as had_schedule_end
            ")
            )
            ->join('habits', 'habits.hab_id','habit_days.had_hab_id')
            ->where('had_hab_id', $habitId)
            ->where('had_status', HabitEnum::ACTIVE->value)
            ->orderByRaw("
                COALESCE(
                    habit_days.had_schedule_ini,
                    habits.hab_schedule_ini
                ) ASC
            ")
            ->get();
    }

    public function getExistHabitDayRegistered($habitDayId, $day, $isNew){

        if ($isNew) {
            return HabitDay::where('had_hab_id', $habitDayId)
                ->where('had_day', $day)
                ->where('had_status', HabitEnum::ACTIVE->value)
                ->exists();
        }

        return false;
    }
}
