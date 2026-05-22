<?php

namespace App\Services;

use App\Enums\HabitEnum;
use App\Helpers\DataHelper;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitDay;
use App\Repositories\HabitRepository;
use DateTime;
use Log;
class HabitDayService extends Controller
{
    public function __construct(
        protected HabitRepository $habitRepository
    ) {}

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
        }
    }

    private function getExpectTotalCustomized($startDate, $habitId)
    {
        $dateCurrent = new DateTime($startDate);
        $monthCurrent = $dateCurrent->format('m');
        $yearCurrent = $dateCurrent->format('Y');

        $countHabitDay = HabitDay::where('had_hab_id', $habitId)->get();
        $countTotal = 0;

        foreach ($countHabitDay as $day) {
            $dayNumber = DataHelper::getDayNumber($day->had_day);
            $countTotal += DateHelper::getCountDaysInWeek($yearCurrent, $monthCurrent, $dayNumber);
        }

        return $countTotal;
    }

    public function getExpectTotalHabitsDone($startDate, $endDate, $habitId, &$groupHabitsComplete) {

        $monthsInRange = DateHelper::getMonthsInRange($startDate, $endDate);
        $detailHabit = Habit::find($habitId);

        foreach ($monthsInRange as $month) {

            $period = DateTime::createFromFormat('Y-m', $month);
            $monthShort = (clone $period)->format('M');

            $start = (clone $period)->modify('first day of this month');
            $end = (clone $period)->modify('last day of this month');

            if ($detailHabit->hab_type_recurrence == HabitEnum::RECURRENCE_PERSONALIZADO->value) {
                $countTotalHabits = $this->getExpectTotalCustomized($startDate, $habitId);
            } else {
                $countTotalHabits = 0;
                $typeRecurrence = $this->getValueTypeRecurrence($detailHabit->hab_type_recurrence, $habitId);

                while ($start <= $end) {
                    $countTotalHabits++;
                    $start->modify($typeRecurrence);
                }
            }

            $groupHabitsComplete[$monthShort] = ["totalDone" => 0];
            $groupHabitsComplete[$monthShort]["totalHabits"] = $countTotalHabits;
        }
    }


    private function existHabitDay($habitDayId, $day) {
        return $this->habitRepository->getExistHabitDayRegistered($habitDayId, $day);
    }

    public function createUpdateOnlyHabitDay(array $habitDay) {

        $had_id = $habitDay['had_id'] ?? null;

        $had_day = $habitDay['had_day'] ?? null;
        $had_description = $habitDay['had_description'] ?? null;
        $had_schedule = $habitDay['had_schedule'] ?? null;

        if ($had_id) {
            $habitDay = HabitDay::find($had_id);

            $existHabitDayRegisterd = $this->existHabitDay($habitDay->had_hab_id, $had_day);

            if ($existHabitDayRegisterd) {
                throw new \Exception("El día seleccionado ya se registro la actividad.");
            }

            $habitDay->update([
                'had_day' => $had_day,
                'had_description' => $had_description,
                'had_schedule' => $had_schedule
            ]);
            return $habitDay;
        } else {

            $had_hab_id = $habitDay['had_hab_id'] ?? null;

            $existHabitDayRegisterd = $this->existHabitDay($had_hab_id, $had_day);

            if ($existHabitDayRegisterd) {
                throw new \Exception("El día del hábito ya existe.");
            }

            return HabitDay::create([
                'had_hab_id' => $had_hab_id,
                'had_day' => $had_day,
                'had_description' => $had_description,
                'had_schedule' => $had_schedule
            ]);
        }

    }
}
