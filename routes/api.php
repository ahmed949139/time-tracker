<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\TimeLogController;
use App\Http\Controllers\API\UserInfoController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/{id}', UserInfoController::class);
    Route::apiResource('/clients', ClientController::class);
    Route::apiResource('/projects', ProjectController::class);
    Route::post('/start-time-log', [TimeLogController::class, 'startTimeLog']);
    Route::patch('/end-time-log/{id}', [TimeLogController::class, 'endTimeLog']);
    Route::get('/time-logs', [TimeLogController::class, 'timeLogs']);
    Route::get('time-logs/export-pdf', [TimeLogController::class, 'exportPdf']);
    Route::get('/report', ReportController::class);
});

