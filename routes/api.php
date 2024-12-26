<?php


use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users',UserController::class)->only(['index','store','show']);
    Route::apiResource('/tasks',TaskController::class)->only(['index','show','destroy']);
});



