<?php

namespace App\Http\Controllers\Api\Project;

use App\Enums\ProjectEnum;
use App\Helpers\UtilHelper;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Validation\ValidationException;

class ProjectController
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function list(){
        try {

            $listProjects = $this->projectService->list();

            return ApiResponse::successResponse(
                $listProjects,
                "projects listed successfully",
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

    public function create(StoreProjectRequest $request){

        try {
            $data = $request->validated();

            $newProject = $this->projectService->create($data);

            return ApiResponse::successResponse(
                $newProject,
                "project registered successfully",
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
