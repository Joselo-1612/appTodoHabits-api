<?php

namespace App\Http\Controllers\Api\Habit;

use App\Enums\HabitEnum;
use App\Http\Requests\Habit\StoreHabitDayRequest;
use App\Http\Responses\ApiResponse;
use App\Models\HabitDay;
use App\Services\HabitDayService;
use Dotenv\Exception\ValidationException;

class HabitDayController
{
    public function __construct(
        protected HabitDayService $habitService
    ) {}

    public function registerHabitDay(StoreHabitDayRequest $request) {
        try {
            $data = $request->validated();

            $newHabit = $this->habitService->createUpdateOnlyHabitDay($data);

            return ApiResponse::successResponse(
                $newHabit,
                "habit day registered succesfully",
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

    public function deleteHabitDay($idHabitDay) {
        try {

            $habitDetail = HabitDay::findOrFail($idHabitDay);
            $habitDetail->update([
                "had_status" => HabitEnum::INACTIVE->value
            ]);

            return ApiResponse::successResponse(
                $habitDetail,
                "habit day delete succesfully",
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
