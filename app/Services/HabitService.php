<?php

namespace App\Services;

use App\Enums\HabitEnum;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitDay;
use App\Repositories\HabitRepository;
use DateTime;
use Log;

class HabitService extends Controller
{

    public function __construct(
        protected HabitDayService $habitDayService,
        protected HabitCompleteService $habitCompleteService,
        protected HabitRepository $habitRepository
    ) {}

    public function registerHabitAndHabitDays($habit) {

        $newHabit = $this->createUpdateHabit($habit);
        $habDaysWeek = $habit["hab_days_of_week"] ?? [];

        if (count($habDaysWeek) > 0) {
            $this->habitDayService->createUpdateDaysHabit($habDaysWeek, $newHabit);
        }

        return $newHabit;
    }

    private function createUpdateHabit(array $habit) {
        if (isset($habit["hab_id"])) {
            $habitFound = Habit::find($habit["hab_id"]);
            $habitFound->update($habit);
            $habitResponse = $habitFound;
        } else {
            $userId = UtilHelper::userSessionId();
            $habit["hab_use_id"] = $userId;

            $habitResponse = Habit::create($habit);
        }

        return $habitResponse;
    }

    public function getDetailHabit($habitId){
        $detailHabit = Habit::find($habitId);

        $listDaysHabit = $this->habitRepository->getListHabitDays($habitId);

        return [
            'detailHabit' => $detailHabit,
            'listDaysHabit' => $listDaysHabit
        ];
    }

    public function getReportCountDoneHabit($startDate, $endDate, $habitId){

        $listHabitsDone = $this->habitCompleteService->getCountAllHabitsDone($startDate, $endDate, $habitId);

        return [
            "listHabitsDone" => $listHabitsDone,
            "totalHabit" => array_sum(array_column($listHabitsDone, "totalHabits")),
            "totalDone" => array_sum(array_column($listHabitsDone, "totalDone"))
        ];
    }
}
