<?php

namespace App\Services;

use App\Enums\ProjectEnum;
use App\Helpers\UtilHelper;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Repositories\ProjectRepository;
use Log;

class ProjectService
{

    public function __construct(
        protected ProjectGroupService $projectGroupService,
        protected ProjectRepository $projectRepository
    ) {}

    public function list() {
        $userId = UtilHelper::userSessionId();

        $listProject = $this->projectRepository->getListProject();

    }

    public function create($dataProject){

        $proGroup = $dataProject['pro_group'];
        $projectGroup = $this->projectGroupService->findGroupProject($proGroup);
        $userId = UtilHelper::userSessionId();

        if ($projectGroup) {
            $projectGroupId = $projectGroup->prg_id;
        } else {
            $newProjectGroup = $this->projectGroupService->create($proGroup);
            $projectGroupId = $newProjectGroup->prg_id;
        }

        return Project::create([
            'pro_id' => $dataProject['pro_id'] ?? null,
            'pro_name' => $dataProject['pro_name'],
            'pro_description' => $dataProject['pro_description'] ?? null,
            'pro_priority' => $dataProject['pro_priority'],
            'pro_date_start' => $dataProject['pro_date_start'],
            'pro_date_end' => $dataProject['pro_date_end'],
            'pro_prg_id' => $projectGroupId,
            'pro_use_id' => $userId
        ]);
    }
}