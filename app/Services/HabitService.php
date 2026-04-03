<?php

namespace App\Services;

use App\Enums\HabitEnum;
use App\Enums\HabitRecurrence;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use Log;

class HabitService extends Controller
{

    public function __construct(
        protected HabitDayService $habitDayService
    ) {}

    public function registerHabitAndHabitDays($habit) {

        $newHabit = $this->createUpdateHabit($habit);

        if (count($habit["hab_days_of_week"]) > 0) {
            $this->habitDayService->createUpdateDaysHabit($habit["hab_days_of_week"], $newHabit);
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
}
