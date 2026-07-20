<?php

namespace App\Services;

use App\Enums\ProjectEnum;
use App\Helpers\UtilHelper;
use App\Models\ActivitySection;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Log;

class ProjectService
{

    public function __construct(
        protected ProjectGroupService $projectGroupService,
        protected ProjectRepository $projectRepository
    ) {}

    public function list() {
        $listProjects = $this->projectRepository->getListProject();
        $listGroupProjects = [];

        foreach ($listProjects as $project) {

            Log::info("Project: " . $project);

            if (!isset($listGroupProjects[$project->prg_name])) {
                $listGroupProjects[$project->prg_name] = [];
            }

            $listGroupProjects[$project->prg_name][] = [
                'pro_id' => $project->pro_id,
                'pro_name' => $project->pro_name
            ];
        }

        return $listGroupProjects;
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

    public function detail($projectId)
    {
        $project = $this->projectRepository->getDetailProjectById($projectId);

        $sections = $project->activitySection()
            ->select('acs_id', 'acs_name')
            ->where('acs_status', ProjectEnum::ACTIVE->value)
            ->with([
                'activities' => function ($query) {
                    $query->select(
                        'act_id',
                        'act_sea_id',
                        'act_name',
                        'act_description',
                        'act_date_start',
                        'act_date_end',
                    )
                    ->with(['tags' => function ($query) {
                        $query->select('tag_color');
                    }])
                    ->orderBy('act_position', 'asc')
                    ->get()
                    ->where('act_status', ProjectEnum::ACTIVE->value);
                }
            ])
            ->get();

        return [
            'detail' => $project,
            'sections' => $sections,
        ];
    }
}