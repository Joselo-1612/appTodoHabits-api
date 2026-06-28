<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ProjectService;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

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
