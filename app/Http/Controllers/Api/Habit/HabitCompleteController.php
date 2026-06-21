<?php

namespace App\Http\Controllers\Api\Habit;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\HabitCompleteService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class HabitCompleteController extends Controller
{

    public function __construct(
        protected HabitCompleteService $habitCompleteService
    ) {}


    public function getlistHabitsComplete(Request $request) {

        try {
            $date = $request->query('period');
            $listHabitsComplete = $this->habitCompleteService->getlistHabitsComplete($date);

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

    public function doneOrSkippedHabit($habitId, Request $request) {

        try {
            $date = $request->query('date');
            $habitComplete = $this->habitCompleteService->doneOrSkippedHabit($habitId, $date);

            return ApiResponse::successResponse(
                $habitComplete,
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
