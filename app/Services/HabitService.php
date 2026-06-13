<?php

namespace App\Services;

use App\Enums\HabitEnum;
use App\Helpers\DataHelper;
use App\Helpers\DateHelper;
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

    public function getListHabitByUser() {
        $userIdSession = UtilHelper::userSessionId();

        return Habit::where('hab_status', HabitEnum::ACTIVE->value)
                    ->where('hab_use_id', $userIdSession)->get();
    }

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

        $detailHabit->hab_days_of_week = $this->habitRepository->getListHabitDays($habitId)->pluck('had_day')->toArray();
        $list_days_of_week = $this->habitRepository->getListHabitDays($habitId)->toArray();

        return [
            'detailHabit' => $detailHabit,
            'listDaysOfWeek' => $list_days_of_week
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

    private function getGenerateScheduleDaily($habit){
        $listDays = DataHelper::getDayStrings();
        $habitsSchedule = collect();

        foreach ($listDays as $value) {

            $habitsSchedule->add((object)[
                'had_id' => $habit->hab_id,
                'had_day' => $value,
                'had_description' => $habit->hab_name,
                'had_schedule_ini' => DateHelper::getConvertDateTiemeToTime($habit->hab_schedule_ini),
                'had_schedule_end' => DateHelper::getConvertDateTiemeToTime($habit->hab_schedule_end)
            ]);
        }

        return $habitsSchedule;
    }

    public function getGenerateSchedule(){
        $listHabits = $this->getListHabitByUser();
        $habitsSchedule = collect();

        foreach ($listHabits as $habit) {
            $days = $this->habitRepository->getListHabitDays($habit->hab_id);

            Log::info("val-days", ['days' => $days]);

            if (count($days) === 0) {
                $daysDaily = $this->getGenerateScheduleDaily($habit);
                $habitsSchedule = $habitsSchedule->merge($daysDaily);
            }

            $habitsSchedule = $habitsSchedule->merge($days);
        }

        return $habitsSchedule->sortBy('had_schedule_ini');
    }
}
