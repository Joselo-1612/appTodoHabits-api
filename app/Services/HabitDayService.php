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

        $isTypeRecurrenceMonthOrWeek = $newHabit->hab_type_recurrence == HabitEnum::RECURRENCE_MENSUAL->value
            || $newHabit->hab_type_recurrence == HabitEnum::RECURRENCE_SEMANAL->value
            || ($newHabit->hab_type_recurrence == HabitEnum::RECURRENCE_PERSONALIZADO->value && $newHabit->hab_is_pinned);

        if (count($habitsDaysFind) > 0) {
            $newHabit->habitDays()->delete();
        }

        foreach ($habitsDays as $day) {
            HabitDay::create([
                'had_day' => $day,
                'had_hab_id' => $newHabit->getHabId(),
                'had_description' => $isTypeRecurrenceMonthOrWeek ? $newHabit->hab_name : null,
                'had_schedule_ini' => $isTypeRecurrenceMonthOrWeek ? $newHabit->hab_schedule_ini : null,
                'had_schedule_end' => $isTypeRecurrenceMonthOrWeek ? $newHabit->hab_schedule_end : null
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


    private function existHabitDay($habitDayId, $day, $isNew) {
        return $this->habitRepository->getExistHabitDayRegistered($habitDayId, $day, $isNew);
    }

    public function createUpdateOnlyHabitDay(array $habitDay) {

        $had_id = $habitDay['had_id'] ?? null;

        $had_day = $habitDay['had_day'] ?? null;
        $had_description = $habitDay['had_description'] ?? null;
        $had_schedule_ini = $habitDay['had_schedule_ini'] ?? null;
        $had_schedule_end = $habitDay['had_schedule_end'] ?? null;
        $had_is_new = $habitDay['had_is_new'];

        if ($had_id) {
            $habitDay = HabitDay::find($had_id);

            $existHabitDayRegisterd = $this->existHabitDay($habitDay->had_hab_id, $had_day, $had_is_new);

            if ($existHabitDayRegisterd) {
                throw new \Exception("El dia seleccionado ya se registro la actividad.");
            }

            $habitDay->update([
                'had_day' => $had_day,
                'had_description' => $had_description,
                'had_schedule_ini' => $had_schedule_ini,
                'had_schedule_end' => $had_schedule_end
            ]);
            return $habitDay;
        } else {

            $had_hab_id = $habitDay['had_hab_id'] ?? null;

            $existHabitDayRegisterd = $this->existHabitDay($had_hab_id, $had_day, $had_is_new);

            if ($existHabitDayRegisterd) {
                throw new \Exception("El día del hábito ya existe.");
            }

            return HabitDay::create([
                'had_hab_id' => $had_hab_id,
                'had_day' => $had_day,
                'had_description' => $had_description,
                'had_schedule_ini' => $had_schedule_ini,
                'had_schedule_end' => $had_schedule_end
            ]);
        }

    }
}
