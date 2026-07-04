<?php

namespace App\Services;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\HabitComplete;
use App\Repositories\HabitRepository;
use DateTime;

class HabitCompleteService
{
    public function __construct(
        protected HabitDayService $habitDayService,
        protected HabitRepository $habitRepository
    ) {}

    public function getlistHabitsComplete($date)
    {
        $currentWeek = DateHelper::getCurrentMonth($date);

        $listHabitsComplete = $this->habitRepository->getListHabitComplete($currentWeek)->toArray();

        $groupHabitsComplete = $this->getGroupHabitByDate($listHabitsComplete);

        return $groupHabitsComplete;
    }

    private function getGroupHabitByDate(array $arrHabitsComplete) {
        $groupHabitsComplete = [];
        $currentMonth = DateHelper::getCurrentMonth();

        foreach ($arrHabitsComplete as $habitComplete) {

            $date = $habitComplete['hac_date'];

            if (!isset($groupHabitsComplete[$date])) {
                $groupHabitsComplete[$date] = ["habits" => []];
            }

            $groupHabitsComplete[$date]["habits"][] = $habitComplete;
        }

        $this->getCompleteWeeksHabitsComplete($currentMonth, $groupHabitsComplete);

        ksort($groupHabitsComplete);

        return $groupHabitsComplete;
    }

    private function getCompleteWeeksHabitsComplete(array $arrMonth, array &$groupHabitsComplete) {

        foreach ($arrMonth as $day) {
            if (!isset($groupHabitsComplete[$day])) {

                $groupHabitsComplete[$day] = [
                    'habits' => []
                ];
            }
        }
    }

    public function doneOrSkippedHabit($habitId, $date)
    {

        $habitComplete = $this->habitRepository->getDetailHabitComplete($habitId, $date);

        if ($habitComplete) {
            $habitComplete->delete();
        } else {
            $habitComplete = HabitComplete::create([
                "hac_hab_id" => $habitId,
                "hac_date" => $date
            ]);
        }

        return $habitComplete;
    }

    public function getTotalHabitsDone($startDate, $endDate, $habitId, &$groupHabitsComplete){
        $listHabitsDone = $this->habitRepository->getListHabitCompleteByPeriod($startDate, $endDate, $habitId);

        foreach ($listHabitsDone->toArray() as $habit) {
            $date = new DateTime($habit['hac_date']);
            $monthShort = (clone $date)->format('M');
            $groupHabitsComplete[$monthShort]["totalDone"]++;
        }
    }

    public function getCountAllHabitsDone($startDate, $endDate, $habitId) {
        $groupHabitsComplete = [];

        $this->habitDayService->getExpectTotalHabitsDone($startDate, $endDate, $habitId, $groupHabitsComplete);
        $this->getTotalHabitsDone($startDate, $endDate, $habitId, $groupHabitsComplete);

        return array_map(function ($month, $habit) {
                return [
                    "month" => $month,
                    "totalDone" => $habit["totalDone"],
                    "totalHabits" => $habit["totalHabits"],
                ];
            },
            array_keys($groupHabitsComplete),
            $groupHabitsComplete
        );
    }
}
