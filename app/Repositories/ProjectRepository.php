<?php

namespace App\Repositories;

use App\Enums\ProjectEnum;
use App\Models\ProjectGroup;

class ProjectRepository
{
    public function getList() {
        return ProjectGroup::where('prg_status', ProjectEnum::ACTIVE->value)
                            ->orderBy('prg_name', 'ASC')
                            ->get();
    }

    public function findGroupProject(string $nameGroup) {
        return ProjectGroup::where('prg_name', 'LIKE', "%{$nameGroup}%")
                            ->where('prg_status', ProjectEnum::ACTIVE->value)
                            ->first();
    }
}