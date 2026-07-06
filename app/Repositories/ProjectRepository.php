<?php

namespace App\Repositories;

use App\Enums\ProjectEnum;
use App\Helpers\UtilHelper;
use App\Models\ActivitySection;
use App\Models\Project;
use App\Models\ProjectGroup;

class ProjectRepository
{
    public function getListProjectGroup() {
        return ProjectGroup::where('prg_status', ProjectEnum::ACTIVE->value)
                            ->orderBy('prg_name', 'ASC')
                            ->get();
    }

    public function getListProject() {
        $userIdSession = UtilHelper::userSessionId();

        return Project::select('pro_id', 'pro_name', 'prg_name')
            ->join('project_groups', 'projects.pro_prg_id', '=', 'project_groups.prg_id')
            ->where('projects.pro_use_id', $userIdSession)
            ->where('projects.pro_status', ProjectEnum::ACTIVE->value)
            ->get();
    }

    public function findGroupProject(string $nameGroup) {
        return ProjectGroup::where('prg_name', 'LIKE', "%{$nameGroup}%")
                            ->where('prg_status', ProjectEnum::ACTIVE->value)
                            ->first();
    }

    public function getListSectionActivities($projectId){
        return ActivitySection::join('activities', 'activity_sections.acs_id', '=', 'activities.act_sea_id')
                                ->where('acs_pro_id', $projectId)
                                ->where('acs_status', ProjectEnum::ACTIVE->value)
                                ->get();
    }
}