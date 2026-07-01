<?php

namespace App\Http\Controllers\Api\Project;

use App\Helpers\UtilHelper;
use App\Http\Responses\ApiResponse;
use App\Services\ProjectGroupService;

class ProjectGroupController
{
    //

    public function __construct(
        protected ProjectGroupService $projectService
    ) {}

    public function list() {

        $sessionName = UtilHelper::sessionNameUser();
        $cachedUser = UtilHelper::cacheUser();

        $projectService = $this->projectService->getList();

        return ApiResponse::successResponse(
            $projectService,
            'Query projects successfully',
            200
        );
    }

}
