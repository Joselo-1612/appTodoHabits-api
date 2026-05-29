<?php

namespace App\Services;

use App\Enums\HabitEnum;
use App\Helpers\DateHelper;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitComplete;
use App\Repositories\HabitRepository;
use DateTime;

class HabitCompleteService extends Controller
{
    public function __construct(
        protected HabitDayService $habitDayService,
        protected HabitRepository $habitRepository
    ) {}

    public function getlistHabitsComplete()
    {
        $currentWeek = DateHelper::getWeekCurrent();

        $listHabitsComplete = $this->habitRepository->getListHabitComplete($currentWeek)->toArray();

        $groupHabitsComplete = $this->getGroupHabitByDate($listHabitsComplete);

        return $groupHabitsComplete;
    }

    private function getGroupHabitByDate(array $arrHabitsComplete) {
        $groupHabitsComplete = [];
        $currentWeek = DateHelper::getWeekCurrent();

        foreach ($arrHabitsComplete as $habitComplete) {

            $date = $habitComplete['hac_date'];

            if (!isset($groupHabitsComplete[$date])) {
                $groupHabitsComplete[$date] = ["habits" => []];
            }

            $groupHabitsComplete[$date]["habits"][] = $habitComplete;
        }

        $this->getCompleteWeeksHabitsComplete($currentWeek, $groupHabitsComplete);

        ksort($groupHabitsComplete);

        $groupHabitsCompleteWithPercentage = $this->getPercentageHabitsComplete($groupHabitsComplete);

        return $groupHabitsCompleteWithPercentage;
    }

    private function getCompleteWeeksHabitsComplete(array $arrWeek, array &$groupHabitsComplete) {

        foreach ($arrWeek as $day) {
            if (!isset($groupHabitsComplete[$day])) {

                $groupHabitsComplete[$day] = [
                    'habits' => []
                ];
            }
        }
    }

    private function getPercentageHabitsComplete(array &$groupHabitsComplete) {

        $userId = UtilHelper::userSessionId();
        $totalHabits = Habit::where('hab_use_id', $userId)->where('hab_status', HabitEnum::ACTIVE->value)->count();

        foreach ($groupHabitsComplete as $date => $habits) {
            $totalHabitsComplete = count($habits["habits"]);
            if ($totalHabits === 0) {
                $percentage = 0;
            } else {
                $percentage = ($totalHabitsComplete / $totalHabits) * 100;
            }
            $groupHabitsComplete[$date]['percentage'] = round($percentage, 2);
        }

        return $groupHabitsComplete;
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
