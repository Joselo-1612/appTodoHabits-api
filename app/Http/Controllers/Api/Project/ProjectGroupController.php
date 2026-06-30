<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectGroupController extends Controller
{
    //

    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function getlistHabitsActive() {

        $sessionName = UtilHelper::sessionNameUser();
        $cachedUser = UtilHelper::cacheUser();

        Log::info("Nombre de usuario en sesión: {$sessionName}");
        Log::info("Usuario en caché: {$cachedUser}");

        $habitService = $this->habitService->getListHabitByUser();

        return ApiResponse::successResponse(
            $habitService,
            'Query habits successfully',
            200
        );
    }

}
