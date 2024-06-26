<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Events Routes that require authentication
Route::apiResource('events', EventController::class)->middleware('auth:sanctum')->except(['index', 'show'])->middleware('throttle:60,1');

// Events Routes that don't require authentication
Route::apiResource('events', EventController::class)->only(['index', 'show']);

Route::apiResource('events.attendees', AttendeeController::class)->scoped()->middleware('auth:sanctum')->except(['index', 'show', 'update', 'destroy']);

//Attendees list that don't require authentication
Route::apiResource('events.attendees', AttendeeController::class)->scoped()->only(['index', 'show', 'destroy']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
