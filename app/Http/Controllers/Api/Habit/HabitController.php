<?php

namespace App\Http\Controllers\Api\Habit;

use App\Enums\HabitEnum;
use App\Helpers\StatusModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habit\StoreHabitRequest;
use App\Http\Responses\ApiResponse;
use App\Services\HabitService;
use App\Models\Habit;
use Illuminate\Validation\ValidationException;

class HabitController extends Controller
{
    public function __construct(
        protected HabitService $habitService
    ) {}

    public function getlistHabitsActive() {

        $listHabits = Habit::where('hab_status', 1)->get();

        return ApiResponse::successResponse(
            $listHabits,
            'Query habits successfully',
            200
        );
    }

    public function registerHabit(StoreHabitRequest $request){
        // funcion registro
        try {
            $data = $request->validated();

            $newHabit = $this->habitService->registerHabitAndHabitDays($data);

            return ApiResponse::successResponse(
                $newHabit,
                "habit registed succesfully",
                201
            );

        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error validating request data',
                422,
                $e->getMessage()
            );
        }
    }

    public function updateHabit(StoreHabitRequest $request, string $habitId) {

        $habitDetail = Habit::findOrFail($habitId);
        $data = $request->validated();
        $habitDetail->update($data);

        return ApiResponse::successResponse(
            $habitDetail->getChanges(),
            "habit updated succesfully",
            201
        );
    }

    public function deleteHabit(string $habitId) {

        $habitDetail = Habit::findOrFail($habitId);
        $habitDetail->update([
            "hab_status" => HabitEnum::INACTIVE->value
        ]);

        return ApiResponse::successResponse(
            $habitDetail->getChanges(),
            "habit deleted succesfully",
            200
        );
    }
}
