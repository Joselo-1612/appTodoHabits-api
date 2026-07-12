<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Requests\Project\StoreActivitySectiontRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ActivitySectionController
{
    public function __construct(
        protected ActivityService $activityService
    ) {}

    public function create(StoreActivitySectiontRequest $request){

        try {
            Log::info("se ingresa a la funcionalidad de registrar una actividad");

            $data = $request->validated();

            Log::info("valores de data", [$data]);

            $newSectionActivity = $this->activityService->createSectionActivity($data);

            return ApiResponse::successResponse(
                $newSectionActivity,
                "activity section created successfully",
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
}
