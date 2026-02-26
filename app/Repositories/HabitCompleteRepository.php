<?php

namespace App\Repositories;

use App\Helpers\UtilHelper;
use App\Models\Habit;
use App\Models\HabitComplete;
use Carbon\Carbon;
use Log;

class HabitCompleteRepository
{

    public function getDetailHabitComplete($habitId)
    {
        $userId = UtilHelper::userSessionId();

        return HabitComplete::join('habits', 'habits.hab_id', '=', 'habit_completes.hac_hab_id')
                ->where('hac_hab_id', $habitId)
                ->where('hac_date', Carbon::now()->format('Y-m-d'))
                ->where('habits.hab_use_id', $userId)
                ->first();
    }

    public function getListHabitComplete()
    {
        $userId = UtilHelper::userSessionId();

        return Habit::join('habit_completes', 'habits.hab_id', 'habit_completes.hac_hab_id')
        ->where('hac_is_done', 1)
        ->where('habits.hab_use_id', $userId)
        ->get();
    }
}
