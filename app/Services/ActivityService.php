<?php

namespace App\Services;

use App\Enums\ProjectEnum;
use App\Models\Activity;
use App\Models\ActivitySection;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Log;

class ActivityService
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

    public function createActivity($data){
        return Activity::create([
            "act_name" => $data['act_name'],
            "act_description" => $data['act_description'],
            "act_date_start" => Carbon::now(),
            "act_date_end" => Carbon::now(),
            "act_sea_id" => $data['act_sea_id'],
            "act_status" => ProjectEnum::ACTIVE->value
        ]);
    }

    public function createSectionActivity(array $data)
    {
        return ActivitySection::create([
            "acs_name" => $data['acs_name'],
            "acs_pro_id" => $data['acs_pro_id'],
            "acs_status" => ProjectEnum::ACTIVE->value
        ]);
    }

    public function updateActivyBySection(int $sectionId, int $activityId){
        return Activity::where('act_id', $activityId)
            ->update(['act_sea_id' => $sectionId]);
    }
}