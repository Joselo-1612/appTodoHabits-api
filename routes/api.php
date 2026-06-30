<?php


use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Habit\HabitController;
use App\Http\Controllers\Api\Habit\HabitCompleteController;
use App\Http\Controllers\Api\Habit\HabitDayController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot_password', [ForgotPasswordController::class, 'forgot'])->name('password.forgot');
    Route::post('reset_password', [ForgotPasswordController::class, 'reset'])->name('password.reset');
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::prefix('habit')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('detail/{id}',[HabitController::class,'getDetailHabit']);
        Route::get('list', [HabitController::class,'getlistHabitsActive']);
        Route::get('list-calendar', [HabitController::class,'getlistHabitsCalendar']);
        Route::get('schedule', [HabitController::class,'generateSchedule']);
        Route::get('report/{startDate}/{endDate}/{habitId}', [HabitController::class,'getReportCountDoneHabit']);
        Route::post('register', [HabitController::class,'registerHabit']);
        Route::delete('delete/{id}', [HabitController::class,'deleteHabit']);
    });
});

Route::prefix('habit-day')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('register-update', [HabitDayController::class,'registerHabitDay']);
        Route::delete('delete/{id}', [HabitDayController::class,'deleteHabitDay']);
    });
});

Route::prefix('habit-complete')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('list', [HabitCompleteController::class,'getlistHabitsComplete']);
        Route::get('done_skipped/{id}', [HabitCompleteController::class,'doneOrSkippedHabit']);
    });
});

Route::prefix('project')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('create', [ProjectController::class,'create']);
    });
});

Route::prefix('project-group')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('list', [ProjectController::class,'list']);
    });
});

