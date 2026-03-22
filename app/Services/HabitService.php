<?php

namespace App\Services;

use App\Enums\HabitRecurrence;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\Habit;

class HabitService extends Controller
{

    public function __construct(
        protected HabitDayService $habitDayService
    ) {}

    public function registerHabitAndHabitDays($habit) {

        $newHabit = $this->createHabit($habit);

        if ($newHabit->getHabTypeRecurrence() == HabitRecurrence::PERSONALIZADO
        && count($habit["hab_days"]) > 0) {
            $this->habitDayService->createDaysHabit($habit["hab_days"], $newHabit);
        }

        return $newHabit;
    }

    private function createHabit(array $habit) {
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
