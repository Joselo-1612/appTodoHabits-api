<?php

namespace App\Services;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitDay;
use DateTime;

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

    public function getValueTypeRecurrence(string $recurrenceType, $habitId){
        switch ($recurrenceType) {
            case 'diaria':
                return '+1 day';
            case 'semanal':
                return '+7 days';
            case 'mensual':
                return '+1 month';
            case 'personalizado':
                $countHabitDay = HabitDay::where('had_hab_id', $habitId)->count();
                return "+$countHabitDay days";
        }
    }

    public function getExpectTotalHabitsDone($startDate, $endDate, $habitId, &$groupHabitsComplete) {

        $monthsInRange = DateHelper::getMonthsInRange($startDate, $endDate);
        $detailHabit = Habit::find($habitId);

        foreach ($monthsInRange as $month) {

            $period = DateTime::createFromFormat('Y-m', $month);
            $monthShort = (clone $period)->format('M');

            $start = (clone $period)->modify('first day of this month');
            $end = (clone $period)->modify('last day of this month');

            $typeRecurrence = $this->getValueTypeRecurrence($detailHabit->hab_type_recurrence, $habitId);

            $countTotalHabits = 0;

            while ($start <= $end) {
                $countTotalHabits++;
                $start->modify($typeRecurrence);
            }

            $groupHabitsComplete[$monthShort] = ["totalDone" => 0];
            $groupHabitsComplete[$monthShort]["totalHabits"] = $countTotalHabits;
        }
    }
}
