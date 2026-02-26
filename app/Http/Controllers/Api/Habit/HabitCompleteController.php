<?php

namespace App\Http\Controllers\Api\Habit;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\HabitCompleteService;
use Illuminate\Validation\ValidationException;

class HabitCompleteController extends Controller
{

    public function __construct(
        protected HabitCompleteService $habitCompleteService
    ) {}


    public function getlistHabitsComplete() {

        try {

            $listHabitsComplete = $this->habitCompleteService->getlistHabitsComplete();

            return ApiResponse::successResponse(
                $listHabitsComplete,
                'Query habits complete successfully',
                200
            );
        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error validating request data',
                422,
                $e->getMessage()
            );
        }
    }

    public function doneOrSkippedHabit($habitId, $status) {

        try {

            $habitComplete = $this->habitCompleteService->doneOrSkippedHabit($habitId, $status);

            return ApiResponse::successResponse(
                $habitComplete->getChanges(),
                "habit complete updated successfully",
                200
            );

        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error validating request data',
                422,
                $e->getMessage()
            );
        }
    }
}
