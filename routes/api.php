<?php

declare(strict_types = 1);

use App\Http\Controllers\HabitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('habits', HabitController::class)
        ->scoped(['habit' => 'uuid']);

    Route::apiResource('habits.logs', HabitController::class)
        ->only(['index', 'store', 'destroy'])
        ->scoped(['habit' => 'uuid', 'log' => 'uuid']);
});
