<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitDay;

class HabitDayService extends Controller
{
    public function createUpdateDaysHabit(array $habitsDays, Habit $newHabit)
    {
        $habitsDaysFind = $newHabit->habitDays()->pluck('had_day')->toArray();

        if (count($habitsDaysFind) > 0) {
            $newHabit->habitDays()->delete();
        }

        foreach ($habitsDays as $day) {
            HabitDay::create([
                'had_day' => $day,
                'had_hab_id' => $newHabit->getHabId(),
            ]);
        }
    }

    public function createUpdateDaysHabitNotPinned(array $habitDay)
    {
    }
}
