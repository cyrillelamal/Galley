<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', 'TaskController');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('listings', 'ListingController');
});
