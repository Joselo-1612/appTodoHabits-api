<?php

namespace App\Services;

use App\Helpers\DateHelper;
use App\Helpers\StatusModel;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitComplete;
use App\Repositories\HabitCompleteRepository;
use Carbon\Carbon;
use Log;

class HabitCompleteService extends Controller
{
    public function __construct(
        protected HabitCompleteRepository $habitCompleteRepository
    ) {}

    public function getlistHabitsComplete()
    {
        $currentWeek = DateHelper::getWeekCurrent();

        $listHabitsComplete = $this->habitCompleteRepository->getListHabitComplete($currentWeek)->toArray();

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
        $totalHabits = Habit::where('hab_use_id', $userId)->count();

        foreach ($groupHabitsComplete as $date => $habits) {
            $totalHabitsComplete = count($habits["habits"]);
            $percentage = ($totalHabitsComplete / $totalHabits) * 100;
            $groupHabitsComplete[$date]['percentage'] = $percentage;
        }

        return $groupHabitsComplete;
    }

    public function doneOrSkippedHabit($habitId, $date)
    {
        $habitComplete = $this->habitCompleteRepository->getDetailHabitComplete($habitId, $date);

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

    // public function writeNoteHabit($habitId, $note)
    // {
    //     $habitComplete = $this->habitCompleteRepository->getDetailHabitComplete($habitId);

    //     if ($habitComplete) {
    //         $habitComplete->update([
    //             "hac_note" => $note
    //         ]);
    //     }

    //     return $habitComplete;
    // }
}
