<?php

namespace App\Services;

use App\Models\ProjectGroup;
use App\Repositories\ProjectRepository;

class ProjectGroupService
{
    public function __construct(
        protected ProjectRepository $projectRepository,
    ) {}

    public function getList() {
        return $this->projectRepository->getListProjectGroup();
    }

    public function findGroupProject(string $nameGroup) {
        return $this->projectRepository->findGroupProject($nameGroup);
    }

    public function create(string $nameProjectGroup) {

        $dataProjectGroup = [
            'prg_name' => $nameProjectGroup,
            'prg_color' => '#f77f00'
        ];

        return ProjectGroup::create($dataProjectGroup);
    }

}