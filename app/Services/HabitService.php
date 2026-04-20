<?php

namespace App\Services;

use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Repositories\HabitRepository;
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

    public function getDetailHabit(string $habitId){

    }

    public function getReportCountDoneHabit($startDate, $endDate, $habitId){

        $listHabitsDone = $this->habitCompleteService->getCountAllHabitsDone($startDate, $endDate, $habitId);

        return $listHabitsDone;
    }
}
