<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectService extends Controller
{

    public function __construct(
        protected ProjectGroupService $projectGroupService,
    ) {}

    public function create($dataProject){

        $proGroup = $dataProject['pro_group'];
        $projectGroup = $this->projectGroupService->findGroupProject($proGroup);

        if ($projectGroup) {
            $projectGroupId = $projectGroup->id;
        } else {
            $newProjectGroup = $this->projectGroupService->create($proGroup);
            $projectGroupId = $newProjectGroup->id;
        }

        $data['pro_group_id'] = $projectGroupId;

        return Project::create($data);
    }
}