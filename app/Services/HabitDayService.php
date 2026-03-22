<?php

namespace App\Services;

use App\Helpers\StatusModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habit\StoreHabitDayRequest;
use App\Models\HabitDay;

class HabitDayService extends Controller
{
    public function getListByHabitUser($habitId)
    {
        return HabitDay::where('habit_id', $habitId)
            ->where('had_status', StatusModel::ACTIVE)
            ->pluck('had_day')
            ->toArray();
    }

    public function createDaysHabit($habitsDays, $newHabit)
    {
        foreach ($habitsDays as $day) {
            $newHabit->habitDays()->create([
                'had_day' => $day,
            ]);
        }
    }
}
