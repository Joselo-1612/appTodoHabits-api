<?php

namespace App\Services;

use App\Helpers\StatusModel;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Models\HabitComplete;
use App\Repositories\HabitCompleteRepository;
use Carbon\Carbon;

class HabitCompleteService extends Controller
{
    public function __construct(
        protected HabitCompleteRepository $habitCompleteRepository
    ) {}

    public function getlistHabitsComplete()
    {
        return $this->habitCompleteRepository->getListHabitComplete();
    }

    public function doneOrSkippedHabit($habitId, $status)
    {
        if ($status == StatusModel::ACTIVE) {
            $habitComplete = HabitComplete::create([
                "hac_hab_id" => $habitId,
                "hac_date" => Carbon::now()->format('Y-m-d'),
                "hac_is_done" => $status
            ]);
        } else {
            $habitComplete = $this->habitCompleteRepository->getDetailHabitComplete($habitId);
            $habitComplete->delete();
        }

        return $habitComplete;
    }

    public function writeNoteHabit($habitId, $note)
    {
        $habitComplete = $this->habitCompleteRepository->getDetailHabitComplete($habitId);

        if ($habitComplete) {
            $habitComplete->update([
                "hac_note" => $note
            ]);
        }

        return $habitComplete;
    }
}
