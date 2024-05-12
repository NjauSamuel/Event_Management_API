<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('events', EventController::class)->middleware('auth:sanctum')->except(['index', 'show']);

Route::apiResource('events.attendees', AttendeeController::class)->scoped()->except(['update']);

Route::post('/login', [AuthController::class, 'login']);
