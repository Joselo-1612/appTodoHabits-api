<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Requests\Project\StoreActivityRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ActivityService;
use Illuminate\Validation\ValidationException;

class ActivityController
{
    public function __construct(
        protected ActivityService $activityService
    ) {}

    public function create(StoreActivityRequest $request){

        try {
            $data = $request->validated();

            $newActivity = $this->activityService->createActivity($data);

            return ApiResponse::successResponse(
                $newActivity,
                "activity created successfully",
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

    public function updateActivitySection(int $sectionId, int $activityId) {
        try {

            $updateActivity = $this->activityService->updateActivyBySection($sectionId, $activityId);

            return ApiResponse::successResponse(
                $updateActivity,
                "activity updated successfully",
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
