<?php

namespace App\Http\Controllers\Api\Habit;

use App\Enums\HabitEnum;
use App\Helpers\UtilHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habit\StoreHabitRequest;
use App\Http\Responses\ApiResponse;
use App\Services\HabitService;
use App\Models\Habit;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;

class HabitController extends Controller
{
    public function __construct(
        protected HabitService $habitService
    ) {}

    public function getlistHabitsActive() {

        $sessionName = UtilHelper::sessionNameUser();
        $cachedUser = UtilHelper::cacheUser();

        Log::info("Nombre de usuario en sesión: {$sessionName}");
        Log::info("Usuario en caché: {$cachedUser}");

        $habitService = $this->habitService->getListHabitByUser();

        return ApiResponse::successResponse(
            $habitService,
            'Query habits successfully',
            200
        );
    }

    public function getDetailHabit(string $habitId){
        try {

            $newHabit = $this->habitService->getDetailHabit($habitId);

            return ApiResponse::successResponse(
                $newHabit,
                "Query detail habit successfully",
                200
            );

        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error generating habit report',
                500,
                $e->getMessage()
            );
        }
    }

    public function getReportCountDoneHabit($startDate, $endDate, $habitId){

        try {

            $newHabit = $this->habitService->getReportCountDoneHabit($startDate, $endDate, $habitId);

            return ApiResponse::successResponse(
                $newHabit,
                "habit report generated successfully",
                200
            );

        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error generating habit report',
                500,
                $e->getMessage()
            );
        }
    }

    public function registerHabit(StoreHabitRequest $request){

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

    public function generateSchedule() {
        $habitService = $this->habitService->getGenerateSchedule();

        $pdf = Pdf::loadView('report.habitsSchedule', [
            'habits' => $habitService
        ])->setPaper('a4', 'landscape');

        return $pdf->download('horario-habitos.pdf');
    }
}
